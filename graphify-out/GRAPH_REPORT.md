# Graph Report - .  (2026-07-25)

## Corpus Check
- 489 files · ~107,482 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2320 nodes · 5068 edges · 211 communities (114 shown, 97 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 98 edges (avg confidence: 0.79)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Notification Tests
- Configuration Permissions
- DatePicker Component
- Table Row Actions
- Configuration DataTables
- DataTable Component
- Product DataTables
- Branch Controller
- Project Documentation
- Authentication
- Payroll Enums
- UI Text Components
- ESLint Config
- Table UI Components
- App Header Alerts
- Payment Enums
- Salary Head Enums
- App Header Navigation
- Company Notifications
- Logo Card Components
- Product Enums
- App Sidebar
- Salary Heads Pages
- Approval Level Controller
- Division Controller
- Category Controller
- Approval Flow Controller
- Brand Controller
- Unit Controller
- Permission Controller
- User Controller
- Vite React Config
- HRBP Controller
- HRBP Controller
- Position Group Controller
- Core Service
- Base DataTables
- Notification Controller
- Resource Transform
- Designation Controller
- Payroll Salary Profile Controller
- Components Config
- User Migration
- Cache Migration
- Delete Confirmation Dialog
- Position Validation
- Enum Labels
- Migration Down
- Migration Down
- Employee Contact Controller
- Employee Document Controller
- Employee Education Controller
- Employee Experience Controller
- App Service Provider
- Composer Dependencies
- React Router
- Base DataTables
- Branch DataTables
- Approval Flow Group Controller
- Payroll Salary Structure Controller
- Composer Scripts
- NPM Scripts
- Auth Pages
- Password Pages
- Form Handling
- Payroll Pages
- Department Controller
- Division Controller
- Unit Controller
- Create Form
- Permissions DataTable
- Approval Flow Group Model
- Axios HTTP
- Composer Dev Dependencies
- Settings Pages
- Company Pages
- Roles DataTable
- Policy Authorization
- OpenCode Config
- React Framework
- Department DataTable
- Employees DataTable
- Payroll Salary Profiles DataTable
- Payroll Disbursement Batches DataTable
- Users DataTable
- Payroll Salary Structure Seeder
- Composer Scripts
- Notification Pages
- Type Definitions
- Configuration DataTables
- Configuration DataTables
- Configuration DataTables
- Configuration DataTables
- Configuration DataTables
- Configuration DataTables
- Payroll DataTables
- Profile Validation
- Request Validation
- Pest Dependencies
- Configuration DataTables
- Configuration DataTables
- Product DataTables
- Dashboard Pages
- Department Validation
- Payroll Salary Structure Validation
- Composer Autoload
- Notification Pages
- Controller Constructor
- Controller Constructor
- Composer Scripts
- OpenCode Config
- UAM Documentation
- React Hooks
- Composer Extra
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- Migrations
- MCP Config
- Graphify Config
- Superpowers Documentation
- shadcn UI Components
- clsx Utility
- cmdk Utility
- date-fns Utility
- Products Documentation
- Global Types
- Headless UI
- Inertia React
- Laravel Vite Plugin
- Lucide Icons
- Next Themes
- Radix Alert Dialog
- Radix Avatar
- Radix Checkbox
- Radix Collapsible
- Radix Dialog
- Radix Dropdown Menu
- Radix Label
- Radix Navigation Menu
- Radix Popover
- Radix Scroll Area
- Radix Select
- Radix Slot
- Radix Tabs
- Radix Toggle
- Radix Toggle Group
- Radix Tooltip
- React Day Picker
- React DOM
- Sonner Toasts
- Tabler Icons
- Tailwind Merge
- Tailwind CSS
- Tailwind Vite Plugin
- Radix Key Enum
- React Types
- React DOM Types
- TypeScript
- Vite
- Vite React Plugin
- Font Config
- H2 ERP Logo

## God Nodes (most connected - your core abstractions)
1. `cn()` - 174 edges
2. `Controller` - 81 edges
3. `CoreService` - 66 edges
4. `Button()` - 58 edges
5. `BaseDataTable` - 51 edges
6. `User` - 46 edges
7. `Input()` - 41 edges
8. `Label()` - 37 edges
9. `MigrationHelper` - 35 edges
10. `breadcrumbItems` - 33 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `MigrationHelper`  [INFERRED]
  database/migrations/0001_01_01_000000_create_users_table.php → app/Helpers/MigrationHelper.php
- `up()` --calls--> `MigrationHelper`  [INFERRED]
  database/migrations/2019_09_15_000011_create_company_user_table.php → app/Helpers/MigrationHelper.php
- `up()` --calls--> `MigrationHelper`  [INFERRED]
  database/migrations/2025_09_12_000000_create_divisions_table.php → app/Helpers/MigrationHelper.php
- `up()` --calls--> `MigrationHelper`  [INFERRED]
  database/migrations/2025_09_13_000000_create_departments_table.php → app/Helpers/MigrationHelper.php
- `up()` --calls--> `MigrationHelper`  [INFERRED]
  database/migrations/2026_03_27_000002_create_employee_contacts_table.php → app/Helpers/MigrationHelper.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Docker Compose Stack Services** — docker_compose_app, docker_compose_nginx, docker_compose_postgres, docker_compose_redis, docker_compose_mailpit, docker_compose_worker [EXTRACTED 1.00]
- **H2-HRMS Module Catalog** — docs_specs_01_uam_user_access_management, docs_specs_02_configuration_configuration, docs_specs_03_employee_employee, docs_specs_4_1_payroll_payroll, docs_specs_05_leave_leave_management, docs_specs_06_attendance_attendance, docs_specs_07_recruitment_recruitment, docs_specs_08_performance_performance_management, docs_specs_09_separation_separation, docs_specs_10_reporting_reporting, docs_specs_11_self_service_self_service, docs_specs_12_notifications_notifications, docs_specs_13_announcements_announcements, docs_specs_14_porducts_products, docs_specs_4_2_payroll_salary_disbursement_salary_disbursement [EXTRACTED 1.00]
- **Laravel web app branding** — public_apple_touch_icon_apple_touch_icon, public_apple_touch_icon_laravel_logo, public_apple_touch_icon_ios_web_app_icon [INFERRED 0.85]
- **favicon branding** — public_favicon_favicon, public_favicon_favicon_icon, public_favicon_brand_identity [INFERRED 0.75]

## Communities (211 total, 97 thin omitted)

### Community 0 - "Notification Tests"
Cohesion: 0.05
Nodes (22): Notification, User, bootHasUlid(), UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase (+14 more)

### Community 1 - "Configuration Permissions"
Cohesion: 0.06
Nodes (19): Branch, Company, Department, Division, Position, PositionGroup, PositionService, AdminSeeder (+11 more)

### Community 2 - "DatePicker Component"
Cohesion: 0.08
Nodes (44): DatePicker(), DatePickerProps, formatLocalDateToYYYYMMDD(), parseYYYYMMDDToLocalDate(), BaseDialogProps, MultiCombobox(), MultiComboboxProps, Option (+36 more)

### Community 3 - "Table Row Actions"
Cohesion: 0.07
Nodes (27): BulkDeleteButtonProps, RowActions(), RowActionsProps, BaseDialog(), DeleteConfirmDialog(), MultiUserCombobox(), Toaster(), UserCombobox() (+19 more)

### Community 4 - "Configuration DataTables"
Cohesion: 0.09
Nodes (15): ApprovalFlow, ApprovalFlowGroupItem, ApprovalLevel, Designation, Hrbp, Unit, EmployeeContact, EmployeeDocument (+7 more)

### Community 5 - "DataTable Component"
Cohesion: 0.08
Nodes (32): ColumnDef, DataTable, DataTableProps, TopProgressBarProps, Select(), SelectContent(), SelectItem(), SelectTrigger() (+24 more)

### Community 6 - "Product DataTables"
Cohesion: 0.07
Nodes (10): ProductController, Brand, Category, Product, ProductItem, Unit, ProductService, Illuminate\Database\Eloquent\Factories\HasFactory (+2 more)

### Community 7 - "Branch Controller"
Cohesion: 0.06
Nodes (8): BranchController, CompanyController, StoreBranchRequest, UpdateBranchRequest, StoreCompanyRequest, UpdateCompanyRequest, BranchService, CompanyService

### Community 8 - "Project Documentation"
Cohesion: 0.12
Nodes (23): Multi-tenancy, Docker app Service, Docker mailpit Service, Docker nginx Service, Docker postgres Service, Docker redis Service, Docker worker Service, CRUD Convention (+15 more)

### Community 9 - "Authentication"
Cohesion: 0.11
Nodes (13): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordResetLinkController, RegisteredUserController, VerifyEmailController (+5 more)

### Community 10 - "Payroll Enums"
Cohesion: 0.07
Nodes (11): label(), options(), label(), options(), PayrollSalaryDisbursementBatchController, Request, DisbursePayrollSalaryDisbursementBatchRequest, StorePayrollSalaryDisbursementBatchRequest (+3 more)

### Community 11 - "UI Text Components"
Cohesion: 0.13
Nodes (17): HeadingSmall(), InputError(), LinkProps, TextLink(), Button(), Input(), Label(), AuthLayout() (+9 more)

### Community 12 - "ESLint Config"
Cohesion: 0.05
Nodes (40): eslint, eslint-config-prettier, @eslint/js, eslint-plugin-react, eslint-plugin-react-hooks, @laravel/vite-plugin-wayfinder, lightningcss-linux-x64-gnu, devDependencies (+32 more)

### Community 13 - "Table UI Components"
Cohesion: 0.08
Nodes (34): Table(), TableBody(), TableCell(), TableHead(), TableHeader(), TableRow(), TabsContent, TabsList (+26 more)

### Community 14 - "App Header Alerts"
Cohesion: 0.09
Nodes (33): AppHeader(), Alert(), AlertDescription(), AlertTitle(), alertVariants, Breadcrumb(), BreadcrumbEllipsis(), BreadcrumbItem() (+25 more)

### Community 15 - "Payment Enums"
Cohesion: 0.08
Nodes (12): label(), options(), label(), options(), label(), options(), Employee, PayrollEmployeeSalaryProfileItem (+4 more)

### Community 16 - "Salary Head Enums"
Cohesion: 0.09
Nodes (13): label(), options(), label(), options(), label(), options(), label(), options() (+5 more)

### Community 17 - "App Header Navigation"
Cohesion: 0.10
Nodes (26): AppHeaderProps, mainNavItems, rightNavItems, Sheet(), SheetContent(), SheetDescription(), SheetFooter(), SheetHeader() (+18 more)

### Community 18 - "Company Notifications"
Cohesion: 0.11
Nodes (21): Notification(), NotificationItem, PaginatedResponse, Badge(), badgeVariants, DropdownMenu(), DropdownMenuCheckboxItem(), DropdownMenuContent() (+13 more)

### Community 19 - "Logo Card Components"
Cohesion: 0.10
Nodes (22): AppLogo(), AppLogoIcon(), Card(), CardContent(), CardDescription(), CardFooter(), CardHeader(), CardTitle() (+14 more)

### Community 20 - "Product Enums"
Cohesion: 0.13
Nodes (14): label(), options(), label(), options(), label(), options(), label(), options() (+6 more)

### Community 21 - "App Sidebar"
Cohesion: 0.12
Nodes (21): AppCompany(), AppSidebar(), CompanySwitcher(), filterSidebarItemsByPermission(), SidebarCollapsibleItem(), Icon(), Collapsible(), CollapsibleContent() (+13 more)

### Community 22 - "Salary Heads Pages"
Cohesion: 0.08
Nodes (20): Checkbox(), breadcrumbs, defaultForm, EnumOption, Props, breadcrumbs, ComboItem, emptyForm (+12 more)

### Community 23 - "Approval Level Controller"
Cohesion: 0.11
Nodes (6): label(), options(), ApprovalLevelController, StoreApprovalLevelRequest, UpdateApprovalLevelRequest, ApprovalLevelService

### Community 24 - "Division Controller"
Cohesion: 0.11
Nodes (6): StoreDivisionRequest, UpdateDivisionRequest, StorePayrollEmployeeSalaryProfileRequest, StoreRoleRequest, UpdateRoleRequest, Illuminate\Foundation\Http\FormRequest

### Community 25 - "Category Controller"
Cohesion: 0.11
Nodes (4): CategoryController, StoreCategoryRequest, UpdateCategoryRequest, CategoryService

### Community 26 - "Approval Flow Controller"
Cohesion: 0.13
Nodes (6): label(), options(), ApprovalFlowController, StoreApprovalFlowRequest, UpdateApprovalFlowRequest, ApprovalFlowService

### Community 27 - "Brand Controller"
Cohesion: 0.11
Nodes (4): BrandController, StoreBrandRequest, UpdateBrandRequest, BrandService

### Community 28 - "Unit Controller"
Cohesion: 0.11
Nodes (4): UnitController, StoreUnitRequest, UpdateUnitRequest, UnitService

### Community 29 - "Permission Controller"
Cohesion: 0.11
Nodes (5): PermissionController, StorePermissionRequest, UpdatePermissionRequest, PermissionResource, PermissionService

### Community 30 - "User Controller"
Cohesion: 0.11
Nodes (4): UserController, StoreUserRequest, UpdateUserRequest, UserService

### Community 31 - "Vite React Config"
Cohesion: 0.09
Nodes (21): resources/js/**/*.d.ts, resources/js/**/*.ts, resources/js/**/*.tsx, ./vendor/tightenco/ziggy, compilerOptions, allowJs, baseUrl, esModuleInterop (+13 more)

### Community 32 - "HRBP Controller"
Cohesion: 0.13
Nodes (17): CommandMenu(), filterSidebarItemsByPermission(), Search(), SearchProps, CommandDialog(), ScrollArea, ScrollBar, BaseNavItem (+9 more)

### Community 33 - "HRBP Controller"
Cohesion: 0.12
Nodes (4): HrbpController, StoreHrbpRequest, UpdateHrbpRequest, HrbpService

### Community 34 - "Position Group Controller"
Cohesion: 0.12
Nodes (4): PositionGroupController, StorePositionGroupRequest, UpdatePositionGroupRequest, PositionGroupService

### Community 36 - "Base DataTables"
Cohesion: 0.12
Nodes (4): ApprovalFlowsDataTable, BrandsDataTable, ProductsDataTable, Illuminate\Database\Eloquent\Builder

### Community 37 - "Notification Controller"
Cohesion: 0.20
Nodes (5): NotificationController, ImpersonateController, HandleInertiaRequests, Illuminate\Http\Request, Inertia\Middleware

### Community 38 - "Resource Transform"
Cohesion: 0.14
Nodes (7): CompanyResource, DepartmentResource, DivisionResource, CategoryResource, RoleResource, UserResource, Illuminate\Http\Resources\Json\JsonResource

### Community 39 - "Designation Controller"
Cohesion: 0.13
Nodes (3): DesignationController, StoreDesignationRequest, UpdateDesignationRequest

### Community 40 - "Payroll Salary Profile Controller"
Cohesion: 0.14
Nodes (4): PayrollEmployeeSalaryProfileController, RoleController, PayrollEmployeeSalaryProfileService, RoleService

### Community 41 - "Components Config"
Cohesion: 0.11
Nodes (17): aliases, components, hooks, lib, ui, utils, iconLibrary, rsc (+9 more)

### Community 43 - "Cache Migration"
Cohesion: 0.12
Nodes (3): CreateCompaniesTable, CreateDomainsTable, Illuminate\Database\Migrations\Migration

### Community 44 - "Delete Confirmation Dialog"
Cohesion: 0.20
Nodes (14): DeleteConfirmDialogProps, AlertDialog(), AlertDialogAction(), AlertDialogCancel(), AlertDialogContent(), AlertDialogDescription(), AlertDialogFooter(), AlertDialogHeader() (+6 more)

### Community 45 - "Position Validation"
Cohesion: 0.12
Nodes (3): PositionController, StorePositionRequest, UpdatePositionRequest

### Community 46 - "Enum Labels"
Cohesion: 0.17
Nodes (6): label(), options(), label(), options(), StoreEmployeeRequest, UpdateEmployeeRequest

### Community 47 - "Migration Down"
Cohesion: 0.12
Nodes (5): up(), up(), up(), up(), up()

### Community 48 - "Migration Down"
Cohesion: 0.12
Nodes (5): MigrationHelper, up(), up(), up(), up()

### Community 49 - "Employee Contact Controller"
Cohesion: 0.16
Nodes (4): EmployeeContactController, StoreEmployeeContactRequest, UpdateEmployeeContactRequest, EmployeeContactService

### Community 50 - "Employee Document Controller"
Cohesion: 0.16
Nodes (4): EmployeeDocumentController, StoreEmployeeDocumentRequest, UpdateEmployeeDocumentRequest, EmployeeDocumentService

### Community 51 - "Employee Education Controller"
Cohesion: 0.16
Nodes (4): EmployeeEducationController, StoreEmployeeEducationRequest, UpdateEmployeeEducationRequest, EmployeeEducationService

### Community 52 - "Employee Experience Controller"
Cohesion: 0.16
Nodes (4): EmployeeExperienceController, StoreEmployeeExperienceRequest, UpdateEmployeeExperienceRequest, EmployeeExperienceService

### Community 53 - "App Service Provider"
Cohesion: 0.18
Nodes (4): AppServiceProvider, TenancyServiceProvider, Illuminate\Support\ServiceProvider, Stancl\Tenancy\Middleware

### Community 54 - "Composer Dependencies"
Cohesion: 0.12
Nodes (15): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+7 more)

### Community 55 - "React Router"
Cohesion: 0.19
Nodes (9): AppContent(), AppContentProps, AppShell(), AppShellProps, Breadcrumbs(), ImpersonationBanner(), SidebarInset(), SearchProvider() (+1 more)

### Community 56 - "Base DataTables"
Cohesion: 0.19
Nodes (4): BaseDataTable, PayrollSalaryHeadsDataTable, Illuminate\Support\Collection, Yajra\DataTables\Services\DataTable

### Community 57 - "Branch DataTables"
Cohesion: 0.16
Nodes (3): BranchesDataTable, UnitsDataTable, Yajra\DataTables\EloquentDataTable

### Community 58 - "Approval Flow Group Controller"
Cohesion: 0.16
Nodes (3): ApprovalFlowGroupController, StoreApprovalFlowGroupRequest, UpdateApprovalFlowGroupRequest

### Community 59 - "Payroll Salary Structure Controller"
Cohesion: 0.18
Nodes (3): PayrollSalaryStructureController, UpdatePayrollSalaryStructureRequest, PayrollSalaryStructureService

### Community 60 - "Composer Scripts"
Cohesion: 0.14
Nodes (14): require, barryvdh/laravel-dompdf, inertiajs/inertia-laravel, laravel/framework, laravel/tinker, laravel/wayfinder, php, rap2hpoutre/fast-excel (+6 more)

### Community 61 - "NPM Scripts"
Cohesion: 0.14
Nodes (14): scripts, dev, post-autoload-dump, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+6 more)

### Community 62 - "Auth Pages"
Cohesion: 0.29
Nodes (10): AppearanceToggleDropdown(), AppearanceToggleTab(), Appearance, applyTheme(), handleSystemThemeChange(), initializeTheme(), mediaQuery(), prefersDark() (+2 more)

### Community 63 - "Password Pages"
Cohesion: 0.20
Nodes (8): AppSidebarHeader(), HeaderProps, Heading(), IconProps, Separator, SidebarTrigger(), SettingsLayout(), sidebarNavItems

### Community 64 - "Form Handling"
Cohesion: 0.28
Nodes (6): CompaniesPermission, HandleAppearance, HandleTenancyFromSession, Closure, Illuminate\Foundation\Configuration\Middleware, Symfony\Component\HttpFoundation\Response

### Community 65 - "Payroll Pages"
Cohesion: 0.22
Nodes (12): ActiveProfile, calcTotals(), DEDUCTION_CATEGORIES, Employee, getCategoryColor(), getCategoryLabel(), GROSS_CATEGORIES, Props (+4 more)

### Community 70 - "Permissions DataTable"
Cohesion: 0.20
Nodes (3): PermissionsDataTable, Permission, Spatie\Permission\Models\Permission

### Community 72 - "Axios HTTP"
Cohesion: 0.18
Nodes (11): axios, class-variance-authority, concurrently, dependencies, axios, class-variance-authority, concurrently, @radix-ui/react-separator (+3 more)

### Community 73 - "Composer Dev Dependencies"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pint, laravel/sail, mockery/mockery (+3 more)

### Community 74 - "Settings Pages"
Cohesion: 0.31
Nodes (7): NavUser(), Avatar(), AvatarFallback(), AvatarImage(), SidebarProvider(), useInitials(), useIsMobile()

### Community 75 - "Company Pages"
Cohesion: 0.22
Nodes (9): Textarea, breadcrumbs, currentYear, defaultForm, Index(), MONTHS, STATUS_COLORS, TYPE_COLORS (+1 more)

### Community 76 - "Roles DataTable"
Cohesion: 0.22
Nodes (3): RolesDataTable, Role, Spatie\Permission\Models\Role

### Community 78 - "OpenCode Config"
Cohesion: 0.20
Nodes (9): command, enabled, type, mcp, laravel-boost, $schema, artisan, boost:mcp (+1 more)

### Community 79 - "React Framework"
Cohesion: 0.27
Nodes (8): react, react, SidebarMenuSkeleton(), ToggleGroup(), ToggleGroupContext, ToggleGroupItem(), Toggle(), toggleVariants

### Community 86 - "Composer Scripts"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 87 - "Notification Pages"
Cohesion: 0.29
Nodes (7): breadcrumbs, getIcon(), Index(), NotificationItem, PaginatedNotifications, PaginationLink, Props

### Community 88 - "Type Definitions"
Cohesion: 0.25
Nodes (7): Auth, BreadcrumbItem, Company, NavGroup, NavItem, SharedData, User

### Community 98 - "Pest Dependencies"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 102 - "Dashboard Pages"
Cohesion: 0.40
Nodes (3): PlaceholderPattern(), PlaceholderPatternProps, breadcrumbs

### Community 105 - "Composer Autoload"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 106 - "Notification Pages"
Cohesion: 0.50
Nodes (4): getIcon(), NotificationItem, Props, Show()

### Community 109 - "Composer Scripts"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 110 - "OpenCode Config"
Cohesion: 0.50
Nodes (3): plugin, $schema, .opencode/plugins/graphify.js

### Community 111 - "UAM Documentation"
Cohesion: 0.50
Nodes (3): brand identity, favicon icon, SVG vector icon

### Community 113 - "Composer Extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **355 isolated node(s):** `php`, `$schema`, `.opencode/plugins/graphify.js`, `$schema`, `style` (+350 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **97 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Authentication` to `Configuration Permissions`, `Product DataTables`, `Branch Controller`, `Payroll Enums`, `Salary Head Enums`, `Product Enums`, `Approval Level Controller`, `Division Controller`, `Category Controller`, `Approval Flow Controller`, `Brand Controller`, `Unit Controller`, `Permission Controller`, `User Controller`, `HRBP Controller`, `Position Group Controller`, `Notification Controller`, `Designation Controller`, `Payroll Salary Profile Controller`, `Position Validation`, `Enum Labels`, `Employee Contact Controller`, `Employee Document Controller`, `Employee Education Controller`, `Employee Experience Controller`, `Approval Flow Group Controller`, `Payroll Salary Structure Controller`, `Department Controller`, `Division Controller`, `Unit Controller`, `Create Form`, `Policy Authorization`, `Profile Validation`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Why does `CoreService` connect `Core Service` to `Configuration Permissions`, `Configuration DataTables`, `Product DataTables`, `Branch Controller`, `Payroll Enums`, `Payment Enums`, `Salary Head Enums`, `Approval Level Controller`, `Category Controller`, `Approval Flow Controller`, `Brand Controller`, `Unit Controller`, `Permission Controller`, `User Controller`, `HRBP Controller`, `Position Group Controller`, `Payroll Salary Profile Controller`, `Employee Contact Controller`, `Employee Document Controller`, `Employee Education Controller`, `Employee Experience Controller`, `Payroll Salary Structure Controller`, `Division Controller`, `Unit Controller`, `Create Form`, `Approval Flow Group Model`, `Payroll Salary Structure Seeder`, `Controller Constructor`, `Controller Constructor`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Why does `dependencies` connect `Axios HTTP` to `ESLint Config`, `clsx Utility`, `cmdk Utility`, `date-fns Utility`, `Global Types`, `Headless UI`, `Inertia React`, `Laravel Vite Plugin`, `Lucide Icons`, `Next Themes`, `Radix Alert Dialog`, `Radix Avatar`, `Radix Checkbox`, `Radix Collapsible`, `Radix Dialog`, `Radix Dropdown Menu`, `Radix Label`, `Radix Navigation Menu`, `Radix Popover`, `Radix Scroll Area`, `Radix Select`, `Radix Slot`, `Radix Tabs`, `Radix Toggle`, `Radix Toggle Group`, `Radix Tooltip`, `React Day Picker`, `React DOM`, `Sonner Toasts`, `Tabler Icons`, `Tailwind Merge`, `Tailwind CSS`, `Tailwind Vite Plugin`, `Radix Key Enum`, `React Types`, `React DOM Types`, `TypeScript`, `Vite`, `Vite React Plugin`, `React Framework`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **What connects `php`, `$schema`, `.opencode/plugins/graphify.js` to the rest of the system?**
  _355 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Notification Tests` be split into smaller, more focused modules?**
  _Cohesion score 0.05179982440737489 - nodes in this community are weakly interconnected._
- **Should `Configuration Permissions` be split into smaller, more focused modules?**
  _Cohesion score 0.056051587301587304 - nodes in this community are weakly interconnected._
- **Should `DatePicker Component` be split into smaller, more focused modules?**
  _Cohesion score 0.07985480943738657 - nodes in this community are weakly interconnected._