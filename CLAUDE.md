# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

H2 ERP is a **multi-tenant ERP system** with a Laravel 13 API backend and a Nuxt 4 SPA frontend. The two halves are fully decoupled: the frontend consumes the backend via REST API using OAuth2 Bearer tokens.

## Development Setup

### Docker (preferred)
```bash
docker compose up -d
```
Starts all 8 services: PHP 8.4-FPM app, Nginx (port 8000), MySQL 8.0 (port 3307), Redis, queue worker, scheduler, Mailhog (port 8026), Adminer (port 8080).

### Local (no Docker)
```bash
# Backend
cd backend
composer install
npm install --ignore-scripts
npm run build
php artisan migrate
composer run dev        # artisan serve + queue:listen + Vite concurrently

# Frontend
cd frontend
pnpm install
pnpm dev                # Nuxt dev server on port 3000
```

## Commands

### Backend (`/backend`)
```bash
composer run test                # Run Pest test suite
composer run dev                 # Start all backend processes concurrently
./vendor/bin/pint                # PHP linting (Laravel Pint)
php artisan test --filter=<name> # Run a single test
php artisan migrate:fresh --seed # Reset and seed database
```

### Frontend (`/frontend`)
```bash
pnpm dev          # Dev server
pnpm build        # Production build
pnpm lint         # ESLint
pnpm typecheck    # TypeScript check
```

## Backend Architecture

### Service Layer Pattern
Controllers are thin — all business logic lives in `app/Services/`. Every feature service extends `BaseService` (`app/Services/BaseService.php`), which provides generic `list`, `create`, `update`, `delete`, and `bulkDelete` methods. Feature services (e.g. `UserService`, `RoleService`) add domain-specific filtering and operations on top.

```
Controller → FormRequest (validation) → Service → Model
                                               ↓
                                          Resource (API response shape)
```

### Multi-Tenancy
Uses `stancl/tenancy` with `Company` as the tenant model. The tenant key column is `company_id`. All tenant-scoped models use the `BelongsToTenant` trait. Tenant resolution is handled by `InitializeTenancyByRequestData` middleware on every authenticated API route. Frontend passes `X-Tenant: <company_id>` header on every request.

### Authentication & Authorization
- **Auth:** Laravel Passport (OAuth2). Guard is `api` (Passport). Tokens are personal access tokens.
- **RBAC:** Spatie Permission. Roles and permissions are scoped per tenant via the `BelongsToTenant` trait on both `Role` and `User` models.
- **Global roles:** `GlobalRoleEnum` (e.g. `SuperAdmin`) bypass tenant-scoped permission checks via the `CompanyPermission` middleware.
- **Middleware chain on protected routes:** `auth:api → InitializeTenancyByRequestData → CompanyPermission`

### API Structure
All routes are under `routes/api.php` with a `v1` prefix. Grouped by domain:
- `POST /v1/auth/{login,register}`
- `/v1/uam/{users,roles,permissions}` — User Access Management
- `POST /v1/configuration/companies/switch` — switches active tenant and returns a new token

### Exports
Excel exports use `maatwebsite/excel`. Each export class lives in `app/Exports/` and is invoked directly from the controller via `Excel::download()`.

## Frontend Architecture

### API Client
All backend calls go through two composables in `app/composables/useApiClient.ts`:
- `apiFetch(path, options)` — reactive `useFetch` wrapper for GET requests
- `apiCall(path, options)` — one-shot `$fetch` wrapper for mutations
- `apiDownload(path, fileName)` — binary download helper

Both automatically inject `Authorization: Bearer <token>` and `X-Tenant: <company_id>` from the nuxt-auth-utils session. A 401 response triggers automatic redirect to login.

### Auth & Session
`nuxt-auth-utils` manages the session. The session object shape is defined in `app/types/index.d.ts` and includes `access_token`, `company_id`, and tenant list. Global auth guard is in `app/middleware/auth.global.ts`.

### Pages & Layouts
- `layouts/default.vue` — main app shell with sidebar and header
- `layouts/auth.vue` — unauthenticated shell (login page)
- Feature pages live under `app/pages/admin/` organized by domain (e.g. `uam/users.vue`)

## Key Configuration Files

| File | Purpose |
|---|---|
| `backend/config/tenancy.php` | Stancl Tenancy — tenant model, key column |
| `backend/config/passport.php` | OAuth2 token lifetimes |
| `backend/config/permission.php` | Spatie Permission — cache, table names |
| `backend/config/auth.php` | Guards (`web`, `api`) and providers |
| `frontend/nuxt.config.ts` | Runtime config, route rules, modules |

## Environment Variables

Backend `.env.example` requires: `APP_KEY`, `DB_*`, `REDIS_*`. Queue and cache default to `database` driver (switch to `redis` in production).

Frontend `.env.example` requires: `NUXT_PUBLIC_API_BASE` (default `http://localhost:8000`).
