# AGENTS.md

## First read: CLAUDE.md

This repo has a detailed `CLAUDE.md` with stack, commands, architecture, and CRUD checklist. Start there. This file adds what that file omits.

## Quick reference

```bash
composer run setup   # install + .env + key + migrate + npm build
composer run dev     # php serve + queue:listen + Vite in parallel (dev)
composer run test    # config:clear + phpunit (SQLite in-memory)
composer run phpstan # level 9
npm run types        # tsc --noEmit
npm run lint         # ESLint (auto-fix)
npm run format       # Prettier (resources/ only)
npm run build:ssr    # client + SSR bundles
php artisan wayfinder:generate  # after route changes
```

## Infrastructure (Docker)

Infra uses PostgreSQL 16 + Redis + Mailpit, defined in `docker-compose.yml`. PHP-FPM via custom Dockerfile. Nginx on port 8000, Mailpit UI on 8026, Redis on 6380. The app is wired for Redis (session, cache, queue) and Postgres — **not** MySQL.

## Tests

- **Database**: PHPUnit uses SQLite `:memory:` (`phpunit.xml`).
- **Tenancy**: Tests must set the active company in session:
  ```php
  $company = Company::create(['name' => '...', 'short_name' => '...']);
  $this->withSession([config('tenancy.company_id_session_key') => $company->id]);
  ```
- **Auth pattern**: `$this->actingAs(User::factory()->create())`
- Feature tests live in `tests/Feature/<Module>/`.

## Laravel Boost & MCP

- `.mcp.json` exposes `artisan boost:mcp` as an MCP server.
- `boost.json` enables skills: `laravel-best-practices`, `wayfinder-development`, `inertia-react-development`, `tailwindcss-development`.
- `.agents/skills/shadcn/SKILL.md` has detailed shadcn/ui rules. Use it when adding/editing UI components.

## Frontend quirks

- **Import alias**: `@/` → `resources/js/`.
- **Icons**: `lucide-react` (from `components.json:iconLibrary`).
- **Tailwind**: v4 (no `tailwind.config.js`, uses `@tailwindcss/vite` plugin).
- **shadcn/ui**: Radix-based (`"rsc": false`), style `"default"`.
- **Route helpers**: Two sources exist:
  - `resources/js/wayfinder/` — auto-generated typed helpers (run `php artisan wayfinder:generate`).
  - `resources/js/routes/` — manually written function-based helpers.
  - Use wayfinder imports for type safety; fall back to Ziggy `route()`.
- **SSR**: Entrypoint at `resources/js/ssr.tsx`, build with `npm run build:ssr`.
- **Form pattern**: Manual `useState` for `form` + `formErrors`, not Inertia `useForm` (only profile/settings pages use it).

## Multi-tenancy

- Active tenant set from session via `HandleTenancyFromSession` middleware.
- All tenant-scoped tables have `company_id`. Models use `BelongsToTenant` trait.
- `Company` model extends Stancl `Tenant` directly.

## Static analysis & formatting

- **PHPStan**: level 9 (strictest).
- **Prettier**: formats `resources/` only, with `prettier-plugin-organize-imports` and `prettier-plugin-tailwindcss`.
- **Rector**: installed but configured with level 0 (opt-in only).
- **Laravel Pint**: available but not in scripts — use `composer run phpstan` for backend analysis.
- **ESLint**: `@eslint/js`, `eslint-plugin-react`, `eslint-plugin-react-hooks`, `typescript-eslint`.

## No CI

No `.github/workflows/` exists. All quality checks are manual.
