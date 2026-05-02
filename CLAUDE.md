# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

**Laravel 12 + React 19 + Inertia.js v2** monolith with SSR.

- **Backend**: Laravel 12, Yajra DataTables, Spatie Permission, Stancl Tenancy
- **Frontend**: React 19, TypeScript 5, Tailwind CSS 4, Shadcn/ui, Lucide + Tabler icons, Sonner
- **Bridge**: Inertia.js + Laravel Wayfinder (typed route helpers auto-generated in `resources/js/wayfinder/`)

## Commands

```bash
composer run dev        # PHP + queue + logs + Vite in parallel (primary dev command)
npm run dev             # frontend only
npm run build
npm run types           # TypeScript check
npm run lint            # ESLint auto-fix
npm run format          # Prettier
composer run test
composer run phpstan
php artisan wayfinder:generate   # regenerate typed route helpers after route changes
```

## Architecture

### Backend

**Controllers** (`app/Http/Controllers/<Module>/<Entity>/`) — constructor-inject service; `index()` calls `DataTable::renderInertia()`; `store/update/destroy` delegate to service; `bulkDelete()` validates `ids[]` array and returns JSON response. No `create()`, `edit()`, or `show()` methods — all forms are modal dialogs on the frontend.

**Services** (`app/Services/<Module>/<Entity>/`) — extend `CoreService` (`app/Services/Core/CoreService.php`), which provides `create/update/delete/find/all/bulkDelete` via the abstract `model()` method returning an Eloquent FQCN.

**DataTables** (`app/DataTables/`) — extend `BaseDataTable` (`app/DataTables/BaseDataTable.php`). Implement `query()`, `dataTable()`, `getColumns()`, `filename()`. The `renderInertia(component, props)` method on `BaseDataTable` handles both JSON export requests (checks `action=excel/csv/pdf/print`) and normal Inertia page renders.

**Form Requests** (`app/Http/Requests/<Module>/<Entity>/`) — `Store<Entity>Request` and `Update<Entity>Request`; `authorize()` always returns `true`.

**Migrations** use `MigrationHelper` for standard fields:
```php
MigrationHelper::ulidField($table);     // ulid (unique, indexed)
MigrationHelper::companyIdField($table); // company_id bigInteger (indexed)
MigrationHelper::commonFields($table);   // created_by, updated_by, timestamps
```

**Models** use two traits: `HasUlid` (auto-generates `ulid` on creation) and `BelongsToTenant` (Stancl tenancy scoping). `Company` extends Stancl `Tenant` with `getCustomColumns() = ['id', 'name', 'short_name']`.

**Enums** (`app/Enums/`) — PHP 8.1 backed enums; implement `label(): string` for display text and `options(): array` for `[['value' => ..., 'label' => ...]]` dropdown data. Cast enums in models via `protected $casts`.

**Multi-tenancy**: Active tenant set from session via `HandleTenancyFromSession` middleware. All tables include `company_id`; models are scoped automatically via `BelongsToTenant`.

**Permissions**: Defined in `PermissionEnum` (string-backed). Full naming convention:
- `READ_<MODULE>` — module-level read (gates sidebar group)
- `READ_<MODULE>_<ENTITY>` — entity-level read
- `CREATE/UPDATE/DELETE_<MODULE>_<ENTITY>` — write operations

### Frontend

**Pages** (`resources/js/pages/<module>/<entity>/index.tsx`) — one file per entity containing list + CRUD dialogs. Standard state: `open`, `isEdit`, `form`, `formErrors`, `selectedIds`, `tableRef`. Mutations use `router.post/put/delete`; bulk delete uses `axios.delete`. On success: call `tableRef.current?.refetch()` to reload the DataTable.

**Skill**: Use the `inertia-react-development` skill when building pages and the `wayfinder-development` skill when wiring frontend to backend routes.

**Components**:
- `DataTable` (`components/data-table/`) — server-side table with sorting, searching, pagination, exports
- `BaseDialog` (`components/dialog/base-dialog.tsx`) — modal wrapper for add/edit forms
- `<AppLayout title breadcrumbs actions>` — page shell with header slots

**Navigation**:
- Sidebar: `resources/js/config/sidebar.ts` — each item has `can` (permission name string) + icon + url
- Breadcrumbs: `resources/js/config/breadcrumbs.ts` — static map of key → `{ title, href }` using Ziggy `route()`

**Routes**: Import from `resources/js/wayfinder/` for type-safe calls; fall back to Ziggy `route('module.entity.action', id)`.

## Route naming convention

```php
Route::name('module.')
    ->prefix('module')
    ->group(function () {
        Route::delete('entity/bulk-delete', [EntityController::class, 'bulkDelete'])
            ->name('entity.bulk-delete');
        Route::resource('entity', EntityController::class);
    });
```

Produces: `module.entity.index`, `module.entity.store`, `module.entity.update`, `module.entity.destroy`, `module.entity.bulk-delete`.

## Adding a new CRUD entity

1. **Migration** (use `MigrationHelper`) → **Model** (add `HasUlid`, `BelongsToTenant`) → **Enum** (if status/type fields) → **Service** (extend `CoreService`, implement `model()`) → **DataTable** (extend `BaseDataTable`) → **Form Requests** (`Store`/`Update`)
2. **Controller** → **Routes** (`Route::resource` + `bulk-delete`) in `routes/web.php` → **Permissions** in `PermissionEnum`
3. **Frontend page** (`index.tsx`) → **sidebar entry** (`sidebar.ts`) → **breadcrumb entry** (`breadcrumbs.ts`) → run `php artisan wayfinder:generate`

## Existing modules

- `Configuration`: companies, branches, divisions, departments, units, designations, desks
- `UAM`: users, roles, permissions
- `Employee`: employees (with sub-tabs: contacts, documents, education, experience)
- `Payroll`: salary-heads, salary-structures, employee-salary-profiles
- `Product`: categories, brands
