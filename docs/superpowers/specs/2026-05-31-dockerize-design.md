# Docker Local Dev Setup — Design Spec

**Date:** 2026-05-31  
**Status:** Approved

---

## Context

The H2 ERP project (Laravel 12 + React 19 + Inertia.js v3) currently has no Docker setup. Developers rely on local WAMP/Valet/Herd or Laravel Sail (installed as a dev dep but never configured). The goal is a clean, transparent Docker Compose stack for **local development only** that:

- Replaces the need for any local PHP, PostgreSQL, or Redis installation
- Lets Vite run on the host for fast HMR (avoids Windows bind-mount slowness)
- Is easy to read, modify, and extend without Sail's black-box magic

---

## Architecture

Six containers on a private `h2-erp` bridge network:

| Container | Image | Internal port | Host port | Purpose |
|---|---|---|---|---|
| `app` | Custom `php:8.2-fpm-alpine` | 9000 | — | Laravel PHP-FPM |
| `nginx` | `nginx:alpine` | 80 | **80** | Web server, proxies PHP to `app` |
| `postgres` | `postgres:16-alpine` | 5432 | **5432** | Central + tenant databases |
| `redis` | `redis:alpine` | 6379 | **6379** | Cache + sessions |
| `mailpit` | `axllent/mailpit` | 8025 / 1025 | **8025** / **1025** | Email catcher (UI + SMTP) |
| `worker` | Same image as `app` | — | — | `php artisan queue:work` |

Vite runs on the **host machine** (`npm run dev` → `localhost:5173`). The browser reaches it directly; no container needed.

---

## File Layout

```
project root/
├── Dockerfile               ← PHP 8.2-FPM Alpine image
├── docker-compose.yml       ← All services wired together
├── .dockerignore            ← Exclude vendor/, node_modules/, .env, etc.
├── .env.docker              ← Docker-specific .env overrides (safe to commit)
└── docker/
    ├── nginx/
    │   └── default.conf     ← Laravel-aware Nginx config
    └── php/
        └── php.ini          ← Memory limits, upload size, etc.
```

---

## Dockerfile

Single-stage `php:8.2-fpm-alpine`. No application code is baked in — the project root is volume-mounted at `/var/www/html`.

**System packages (apk):**
- `git`, `curl`, `unzip` — general tooling
- `libpng-dev`, `freetype-dev`, `libjpeg-turbo-dev` — GD image library
- `libzip-dev`, `zip` — ZIP support
- `oniguruma-dev` — mbstring dependency
- `icu-dev` — intl extension
- `libpq-dev` — PostgreSQL client library (pdo_pgsql)

**PHP extensions:**
- `pdo_pgsql`, `pgsql` — PostgreSQL connectivity
- `mbstring`, `bcmath`, `intl` — string / math / i18n
- `gd` (with freetype + jpeg) — Spatie MediaLibrary image processing
- `zip` — DomPDF + FastExcel
- `exif` — MediaLibrary image metadata
- `pcntl` — queue worker process signals (graceful shutdown)
- `opcache` — runtime performance

**Composer 2** copied from the official `composer:2` image.

No `composer install` at build time — dependencies come from the host volume mount. Run `composer install` once after first `docker compose up`.

---

## docker-compose.yml

### `app` service
- Build from `Dockerfile`
- Volume: `.:/var/www/html`
- Depends on `postgres` (health check) and `redis`
- Shares `h2-erp` network

### `nginx` service
- `nginx:alpine`
- Volumes: `.:/var/www/html` (for static assets in `public/`) + `./docker/nginx/default.conf`
- Port `80:80`
- Depends on `app`

### `postgres` service
- `postgres:16-alpine`
- Environment: `POSTGRES_DB=h2_erp`, `POSTGRES_USER=postgres`, `POSTGRES_PASSWORD=secret`
- Named volume: `postgres_data:/var/lib/postgresql/data`
- Port `5432:5432`
- Health check: `pg_isready -U postgres`

### `redis` service
- `redis:alpine`
- Named volume: `redis_data:/data`
- Port `6379:6379`

### `mailpit` service
- `axllent/mailpit`
- Ports `8025:8025` (web UI) + `1025:1025` (SMTP)

### `worker` service
- Same build as `app`
- Command: `php artisan queue:work --sleep=3 --tries=3 --timeout=90`
- Same volume mount as `app`
- Depends on `postgres` (healthy) + `redis`
- Restarts on failure: `restart: unless-stopped`

---

## Nginx Config (`docker/nginx/default.conf`)

Standard Laravel pattern:
- `root /var/www/html/public`
- `index index.php`
- `try_files $uri $uri/ /index.php?$query_string`
- FastCGI pass to `app:9000` with standard PHP-FPM params
- `client_max_body_size 100M` (for media uploads)

---

## PHP Config (`docker/php/php.ini`)

```ini
memory_limit = 256M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 120
opcache.enable = 1
opcache.memory_consumption = 128
```

---

## Environment (.env.docker)

Docker-specific overrides to copy on first run. Committed to the repo (no secrets):

```env
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=h2_erp
DB_USERNAME=postgres
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

CACHE_STORE=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

Cache and session switch to Redis (much faster than database drivers). Queue stays on `database` for easier dev inspection.

---

## .dockerignore

```
vendor/
node_modules/
.git/
.env
bootstrap/cache/
storage/logs/
public/build/
*.log
```

---

## Developer Workflow

### First-time setup
```bash
cp .env.docker .env                        # use Docker-specific env
docker compose up -d                       # start all containers
docker compose exec app composer install   # install PHP deps
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
npm install && npm run dev                 # start Vite on host
```

### Daily use
```bash
docker compose up -d      # start stack
npm run dev               # start Vite on host (separate terminal)
docker compose down       # stop stack
docker compose logs -f    # tail all logs
docker compose exec app php artisan <command>
```

### URLs
| Service | URL |
|---|---|
| App | http://localhost |
| Vite HMR | http://localhost:5173 (host) |
| Mailpit | http://localhost:8025 |
| PostgreSQL | localhost:5432 |
| Redis | localhost:6379 |

---

## Stancl Tenancy Notes

Tenancy uses **separate databases per tenant**. The PostgreSQL container hosts the central database (`h2_erp`) plus all dynamically created tenant databases (`tenant{id}`). No extra configuration needed — Tenancy creates tenant DBs at runtime using the same `postgres` connection.

---

## Verification

1. `docker compose up -d` — all 6 containers reach `running` state
2. `docker compose exec app php artisan migrate` — runs without DB errors
3. `http://localhost` — Laravel welcome/login page loads
4. `npm run dev` + reload — Vite HMR assets inject correctly (no 404 on JS/CSS)
5. `http://localhost:8025` — Mailpit UI loads
6. Send a test mail (`php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('a@b.com'))`) — appears in Mailpit
7. `docker compose exec worker php artisan queue:work --once` — processes a job
