# AGENTS.md

## First read

Start with `CLAUDE.md`. It has the full stack, commands, CRUD checklist, and architecture. This file only adds the hard-to-infer repo details.

## Quick reference

```bash
# Setup & dev
composer run setup       # install, .env, key, migrate, npm install, npm run build
composer run dev         # php artisan serve + queue:listen + npm run dev (parallel)
npm run dev              # frontend only

# Verification
npm run types            # tsc --noEmit
npm run lint             # ESLint --fix
npm run format           # Prettier on resources/
composer run test        # config:clear + phpunit (SQLite in-memory)
vendor/bin/phpstan analyse  # PHPStan level 9 (no composer script exists)
php artisan test --filter=DashboardTest  # run a single test

# Build
npm run build            # client bundle
npm run build:ssr        # client + SSR bundles

# Codegen
php artisan wayfinder:generate  # regenerate typed route helpers after route changes
```

## Trust the config over the README

`README.md` is stale/incomplete: it lists Inertia v2 and MySQL. The real stack is Laravel 12 + React 19 + Inertia v3 (see `composer.json`/`package.json`) with PostgreSQL and Redis.

## Infrastructure

`docker-compose.yml` runs PostgreSQL 16 (port 5432), Redis (port 6380), Nginx (port 8000), Mailpit UI (port 8026), and a queue worker. `.env.example` is wired for Postgres + Redis, not MySQL.

## Multi-tenancy

- `Company` (`app/Models/Configuration/Company/Company.php`) extends `Stancl\Tenancy\Database\Models\Tenant`.
- Active tenant is initialized from the session key `config('tenancy.company_id_session_key')` (value: `company_id`) via `HandleTenancyFromSession`.
- Tenant-scoped models use `Stancl\Tenancy\Database\Concerns\BelongsToTenant` and `App\Traits\HasUlid`.
- All tenant-scoped tables have `company_id`; migrations use `MigrationHelper::companyIdField()`.

## Backend conventions

- Controllers: `app/Http/Controllers/<Module>/<Entity>/`
- Services: `app/Services/<Module>/<Entity>/`, extend `CoreService`, implement `model()`.
- DataTables: `app/DataTables/<Module>/<Entity>/`, extend `BaseDataTable`, call `renderInertia()` from `index()`.
- Form Requests: `app/Http/Requests/<Module>/<Entity>/Store` and `Update`.
- Permissions: `app/Enums/Uam/PermissionEnum.php` with names like `READ_MODULE_ENTITY`, `CREATE_MODULE_ENTITY`, etc.

## Route convention

```php
Route::name('module.')
    ->prefix('module')
    ->group(function () {
        Route::delete('entity/bulk-delete', [EntityController::class, 'bulkDelete'])
            ->name('entity.bulk-delete');
        Route::resource('entity', EntityController::class);
    });
```

## Frontend

- Tailwind CSS v4 via `@tailwindcss/vite`. There is no `tailwind.config.js` (`components.json` still references one but it is stale).
- Import alias `@/` maps to `resources/js/`.
- shadcn/ui: Radix-based (`rsc: false`), style `default`, icons from `lucide-react`.
- Route helpers are generated into `resources/js/wayfinder/` (gitignored) by `@laravel/vite-plugin-wayfinder` and `php artisan wayfinder:generate`. Current pages use Ziggy `route()` directly; prefer wayfinder imports when writing new route code.
- CRUD forms use manual `useState` for `form` + `formErrors`, not Inertia `useForm` (which is only used on profile/settings pages).

## Tests

- PHPUnit uses SQLite in-memory (`phpunit.xml`).
- Existing tests use `actingAs(User::factory()->create())` only.
- For tenant-scoped feature tests, set the active company in session before the request:
  ```php
  $company = Company::create(['name' => '...', 'short_name' => '...']);
  $this->withSession([config('tenancy.company_id_session_key') => $company->id]);
  ```

## Static analysis & formatting

- PHPStan: level 9 (`phpstan.neon`). No `composer run phpstan` script exists.
- Prettier formats `resources/` only.
- Rector is installed (`rector.php`) with level 0 — opt-in.
- Laravel Pint is installed but not wired into any scripts.
- ESLint uses `@eslint/js`, `eslint-plugin-react`, `eslint-plugin-react-hooks`, `typescript-eslint`.

## No CI

No `.github/workflows/` exists.

## MCP & skills

- `.mcp.json` exposes `php artisan boost:mcp` as an MCP server.
- `boost.json` enables Laravel/Inertia/React/Tailwind skills.
- For shadcn/ui work, read `.agents/skills/shadcn/SKILL.md` first.

## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

When the user types `/graphify`, use the installed graphify skill or instructions before doing anything else.

Rules:
- For codebase questions, first run `graphify query "<question>"` when graphify-out/graph.json exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- Dirty graphify-out/ files are expected after hooks or incremental updates; dirty graph files are not a reason to skip graphify. Only skip graphify if the task is about stale or incorrect graph output, or the user explicitly says not to use it.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
