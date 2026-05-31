# Docker Local Dev Setup — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a Docker Compose stack for local development with PHP-FPM, Nginx, PostgreSQL, Redis, Mailpit, and a queue worker.

**Architecture:** Custom `php:8.2-fpm-alpine` image with all required PHP extensions; Nginx proxies HTTP to the PHP-FPM container on port 9000; PostgreSQL hosts both the central database and dynamically-created Stancl Tenancy databases; Vite runs on the host machine (not in Docker) for fast HMR.

**Tech Stack:** Docker Compose v2, PHP 8.2-FPM Alpine, Nginx Alpine, PostgreSQL 16 Alpine, Redis Alpine, Mailpit, Composer 2.

---

## Files to Create

| File | Purpose |
|---|---|
| `Dockerfile` | PHP 8.2-FPM Alpine image with all required extensions |
| `docker-compose.yml` | Wires app, nginx, postgres, redis, mailpit, worker |
| `.dockerignore` | Keeps build context lean |
| `.env.docker` | Complete .env for Docker dev (safe to commit) |
| `docker/nginx/default.conf` | Laravel-aware Nginx server block |
| `docker/php/php.ini` | PHP memory/upload limits and opcache config |

---

## Task 1: Create the Dockerfile

**Files:**
- Create: `Dockerfile`

- [ ] **Step 1: Create `Dockerfile`**

```dockerfile
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    unzip \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    zip \
    oniguruma-dev \
    icu-dev \
    libpq-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        mbstring \
        bcmath \
        intl \
        gd \
        zip \
        exif \
        pcntl \
        opcache

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
```

- [ ] **Step 2: Verify the Dockerfile builds cleanly**

Run:
```bash
docker build -t h2-erp-app .
```

Expected: Build completes with `Successfully tagged h2-erp-app:latest` and no errors. The `docker-php-ext-install` step may take 2–4 minutes on first run.

- [ ] **Step 3: Confirm PHP extensions are installed in the image**

Run:
```bash
docker run --rm h2-erp-app php -m
```

Expected output includes: `pdo_pgsql`, `pgsql`, `mbstring`, `bcmath`, `intl`, `gd`, `zip`, `exif`, `pcntl`, `redis`, `Zend OPcache`.

- [ ] **Step 4: Commit**

```bash
git add Dockerfile
git commit -m "feat(docker): add PHP 8.2-FPM Alpine Dockerfile"
```

---

## Task 2: Create PHP runtime config

**Files:**
- Create: `docker/php/php.ini`

- [ ] **Step 1: Create `docker/php/php.ini`**

```ini
memory_limit = 256M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 120

opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 0
opcache.validate_timestamps = 1
```

- [ ] **Step 2: Commit**

```bash
git add docker/php/php.ini
git commit -m "feat(docker): add PHP runtime config"
```

---

## Task 3: Create Nginx config

**Files:**
- Create: `docker/nginx/default.conf`

- [ ] **Step 1: Create `docker/nginx/default.conf`**

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    client_max_body_size 100M;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add docker/nginx/default.conf
git commit -m "feat(docker): add Nginx Laravel config"
```

---

## Task 4: Create `.dockerignore`

**Files:**
- Create: `.dockerignore`

- [ ] **Step 1: Create `.dockerignore`**

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

- [ ] **Step 2: Commit**

```bash
git add .dockerignore
git commit -m "feat(docker): add .dockerignore"
```

---

## Task 5: Create `.env.docker`

**Files:**
- Create: `.env.docker`

This is a complete `.env` file based on `.env.example` with Docker-specific values. It is safe to commit — passwords here are local-dev only.

- [ ] **Step 1: Create `.env.docker`**

```env
APP_NAME="H2 ERP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=h2_erp
DB_USERNAME=postgres
DB_PASSWORD=secret

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

- [ ] **Step 2: Commit**

```bash
git add .env.docker
git commit -m "feat(docker): add Docker-specific env template"
```

---

## Task 6: Create `docker-compose.yml`

**Files:**
- Create: `docker-compose.yml`

- [ ] **Step 1: Create `docker-compose.yml`**

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - .:/var/www/html
      - ./docker/php/php.ini:/usr/local/etc/php/conf.d/custom.ini
    networks:
      - h2-erp
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_started

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - h2-erp
    depends_on:
      - app

  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: h2_erp
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: secret
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"
    networks:
      - h2-erp
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 5s
      timeout: 5s
      retries: 10

  redis:
    image: redis:alpine
    volumes:
      - redis_data:/data
    ports:
      - "6379:6379"
    networks:
      - h2-erp

  mailpit:
    image: axllent/mailpit:latest
    ports:
      - "8025:8025"
      - "1025:1025"
    networks:
      - h2-erp

  worker:
    build:
      context: .
      dockerfile: Dockerfile
    command: php artisan queue:work --sleep=3 --tries=3 --timeout=90
    volumes:
      - .:/var/www/html
      - ./docker/php/php.ini:/usr/local/etc/php/conf.d/custom.ini
    networks:
      - h2-erp
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_started
    restart: unless-stopped

volumes:
  postgres_data:
  redis_data:

networks:
  h2-erp:
    driver: bridge
```

- [ ] **Step 2: Validate the Compose file syntax**

Run:
```bash
docker compose config
```

Expected: Prints the resolved config with no errors. Six services listed: app, nginx, postgres, redis, mailpit, worker.

- [ ] **Step 3: Commit**

```bash
git add docker-compose.yml
git commit -m "feat(docker): add Docker Compose stack with all services"
```

---

## Task 7: First-time stack setup and smoke test

This task verifies the entire stack works end-to-end. No new files — just running commands.

- [ ] **Step 1: Copy the Docker env file**

```bash
cp .env.docker .env
```

If you already have a `.env`, back it up first: `cp .env .env.bak`

- [ ] **Step 2: Start all containers**

```bash
docker compose up -d
```

Expected: All 6 containers start. The `postgres` container will take a few seconds to pass its health check before `app` and `worker` start.

Verify:
```bash
docker compose ps
```

Expected: All services show `running` or `Up`. The worker may show `Up` alongside its command `php artisan queue:work`.

- [ ] **Step 3: Install PHP dependencies**

```bash
docker compose exec app composer install
```

Expected: Composer installs all packages. Last line: `Generating optimized autoload files`.

- [ ] **Step 4: Generate application key**

```bash
docker compose exec app php artisan key:generate
```

Expected: `Application key set successfully.` The `APP_KEY` in your `.env` is now populated.

- [ ] **Step 5: Fix storage permissions**

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

Expected: No output (success).

- [ ] **Step 6: Run migrations and seed**

```bash
docker compose exec app php artisan migrate --seed
```

Expected: Migration table created, all migration files run, seeders execute without errors.

- [ ] **Step 7: Create storage symlink**

```bash
docker compose exec app php artisan storage:link
```

Expected: `The [public/storage] link has been connected to [storage/app/public].`

- [ ] **Step 8: Verify the app loads**

Open `http://localhost` in a browser.

Expected: The H2 ERP login page renders (HTML, no PHP errors, no blank page).

- [ ] **Step 9: Start Vite on the host**

In a separate terminal on your host machine:
```bash
npm install
npm run dev
```

Expected: Vite starts at `http://localhost:5173`. Reload `http://localhost` — CSS and JS assets inject correctly with no 404s in the browser console.

- [ ] **Step 10: Verify Mailpit**

Open `http://localhost:8025` in a browser.

Expected: Mailpit web UI loads showing an empty inbox.

Send a test email from Tinker:
```bash
docker compose exec app php artisan tinker
```
Then inside Tinker:
```php
Mail::raw('Docker mail test', fn($m) => $m->to('test@example.com')->subject('Test'));
```

Expected: The email appears in the Mailpit inbox at `http://localhost:8025`.

- [ ] **Step 11: Verify the queue worker**

Push a test job:
```bash
docker compose exec app php artisan tinker
```
Inside Tinker:
```php
dispatch(fn() => \Log::info('Queue worker is alive'));
```

Check the worker log:
```bash
docker compose logs worker
```

Expected: The worker log shows the job was processed.

- [ ] **Step 12: Final commit**

```bash
git add .env.docker  # if not already committed
git commit -m "feat(docker): complete Docker local dev stack — postgres, redis, mailpit, queue worker"
```

---

## Daily Developer Commands (Reference)

```bash
# Start
docker compose up -d && npm run dev

# Stop
docker compose down

# Logs
docker compose logs -f
docker compose logs -f app      # just PHP-FPM
docker compose logs -f worker   # just queue worker

# Artisan
docker compose exec app php artisan <command>

# Composer
docker compose exec app composer <command>

# Rebuild after Dockerfile changes
docker compose build --no-cache
docker compose up -d
```

## Service URLs

| Service | URL |
|---|---|
| App | http://localhost |
| Vite HMR | http://localhost:5173 (host) |
| Mailpit | http://localhost:8025 |
| PostgreSQL | localhost:5432 |
| Redis | localhost:6379 |
