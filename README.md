# H2-ERP

**H2-ERP** is a community-driven, open-source SaaS ERP covering HR, payroll, leave, products, user access management (UAM) and configuration.

> ⚠️ This project is under active development. Features may be incomplete and subject to change.

---

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** React 19, TypeScript 5
- **Bridge:** Inertia.js v3
- **Styling:** Tailwind CSS 4, shadcn/ui (Radix)
- **Permissions:** Spatie Permission (teams keyed by company)
- **Multi-tenancy:** Stancl Tenancy (single DB, `company_id` column)
- **Tables:** Yajra Laravel DataTables
- **Media:** Spatie Media Library
- **Database:** PostgreSQL, Redis
- **Build Tool:** Vite

---

## Getting Started

### Requirements

- PHP 8.3+
- Composer
- Node.js 20+
- PostgreSQL
- Redis

### Installation

```bash
git clone https://github.com/devghor/h2-erp.git
cd h2-erp

composer run setup   # install, .env, key, migrate, npm install, build
```

Or manually:

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
# configure DB_* (pgsql) and Redis in .env
php artisan migrate
```

### Development

```bash
composer run dev     # server + queue + vite
```

---

## Quality Checks

```bash
composer run test          # config:clear, pint --test, phpstan, phpunit
composer run lint          # pint (fix)
composer run types:check   # phpstan (level 9)
npm run lint:check
npm run format:check
npm run types:check        # tsc
composer run ci:check      # full JS + PHP chain
```

Tests use in-memory SQLite.

After route changes, regenerate typed route helpers:

```bash
php artisan wayfinder:generate
```

---

## Documentation

Feature specs per module live in [docs/specs/](docs/specs/) (start with `00-index.md`). Repo conventions are in `AGENTS.md` and `CLAUDE.md`.
