# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

Also read `AGENTS.md` (repo-specific conventions: tenancy, route convention, test session setup, tooling notes). `README.md` is stale (says Inertia v2 / MySQL); trust `composer.json` / `package.json`.

## Stack

H2 ERP (HR, payroll, leave, product, UAM, configuration modules). Laravel 13 + PHP 8.3, React 19 + Inertia v3 + TypeScript, Tailwind v4, shadcn/ui (Radix), PostgreSQL + Redis. Multi-tenant via `stancl/tenancy` (single DB, `company_id` column). Permissions via `spatie/laravel-permission` (teams keyed by company, see `CompaniesPermission` middleware). Tables via `yajra/laravel-datatables`. Media via `spatie/laravel-medialibrary`.

## Commands

```bash
composer run setup        # install, .env, key, migrate, npm install, build
composer run dev          # runs `php artisan dev` (server + queue + vite)
composer run test         # config:clear, pint --test, phpstan, then `artisan test`
php artisan test --filter=DashboardTest   # single test / class
composer run lint         # pint (PHP fix); lint:check = no-write
composer run types:check  # phpstan analyse (level 9)
npm run lint:check | format:check | types:check   # ESLint / Prettier (resources/) / tsc
npm run lint | format     # auto-fix variants
npm run build:ssr         # client + SSR bundles
php artisan wayfinder:generate   # regenerate typed route helpers after route changes
```

`composer run ci:check` runs the full JS + PHP check chain. Note `composer run test` includes lint and static analysis, not only PHPUnit. Tests use SQLite in-memory (`phpunit.xml`).

## Architecture

Request flow for a CRUD entity (e.g. Branch) spans several files; follow this layering:

- **Route** (`routes/web.php`, inside `auth`+`verified` group): `Route::name('module.')->prefix('module')`, `entity/bulk-delete` registered before `Route::resource`.
- **Controller** `app/Http/Controllers/<Module>/<Entity>/`: thin. Injects Service(s) and a DataTable. `index()` returns `$dataTable->renderInertia('module/entity/index', $props)`. `store/update/destroy/bulkDelete` call the service and redirect back with a flash `success`. `create/show/edit` are empty stubs (UI uses dialogs on the index page).
- **Service** `app/Services/<Module>/<Entity>/` extends `Services\Core\CoreService` (generic all/find/create/update/delete/bulkDelete over the model returned by `model()`); add domain methods (e.g. option lists) here.
- **DataTable** `app/DataTables/<Module>/<Entity>/` extends `BaseDataTable`. `renderInertia()` serves JSON for ajax/export (`excel|csv|pdf|print` via `action` param) and an Inertia page otherwise. HTML builder is unused; columns come from `getColumns()`.
- **Form Requests** `app/Http/Requests/<Module>/<Entity>/Store*Request`, `Update*Request`.
- **Model** `app/Models/<Module>/`: tenant-scoped models use `BelongsToTenant` + `HasUlid`; migrations use `MigrationHelper::companyIdField()`.
- **Permissions**: add cases to `app/Enums/Uam/PermissionEnum.php` and seed via `PermissionSeeder`.
- **Frontend page** `resources/js/pages/<module>/<entities>/index.tsx` uses the shared `components/data-table/data-table.tsx` (server-side table, bulk delete, CSV/print export), dialogs under `components/dialog`, and `useState` forms with manual error state (not Inertia `useForm`). Sidebar entries in `resources/js/config/sidebar.ts`, breadcrumbs in `config/breadcrumbs.ts`.

Middleware order (`bootstrap/app.php`): `HandleTenancyFromSession` (initializes tenant from session `company_id`) then `CompaniesPermission` (sets permission team) then Inertia shared props. Code touching tenant data in tests/console needs an active company.

`resources/js/wayfinder/` and `resources/js/actions/` are generated; don't hand-edit. Prefer wayfinder imports over Ziggy `route()` in new code.

Feature specs live in `docs/specs/` (numbered per module, `00-index.md` first); consult them for intended behavior of unbuilt/partial modules.

## graphify

Knowledge graph at `graphify-out/` (if present): for codebase questions run `graphify query "<question>"` first; run `graphify update .` after code changes.
