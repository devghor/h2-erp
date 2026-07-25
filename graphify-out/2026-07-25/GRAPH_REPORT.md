# Graph Report - .  (2026-07-25)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1747 nodes · 4173 edges · 104 communities (75 shown, 29 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 20 edges (avg confidence: 0.76)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `a2ee77c0`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- salary-structures/index.tsx
- command.tsx
- roles/edit.tsx
- PayrollSalaryHeadController.php
- data-table.tsx
- cn
- Illuminate\Database\Eloquent\Model
- ApprovalLevelController
- app-sidebar.tsx
- ApprovalFlowGroup
- PayrollSalaryStructureController
- bulk-delete-button.tsx
- CategoryController
- HrbpController
- PositionGroupController
- UnitController
- BrandController
- UnitController
- dashboard.tsx
- Illuminate\Http\Request
- Illuminate\Database\Eloquent\Builder
- DesignationController
- DivisionController
- EmployeeContactController
- EmployeeDocumentController
- EmployeeEducationController
- EmployeeExperienceController
- Illuminate\Foundation\Http\FormRequest
- EmployeeController.php
- Employee
- TenancyServiceProvider
- User
- RoleController
- BranchController
- BaseDataTable
- ApprovalFlowTypeEnum.php
- HandleInertiaRequests.php
- CompanyController
- EmployeeController
- PayrollEmployeeSalaryProfileController
- Closure
- ApprovalFlowController
- PositionController
- button.tsx
- PayrollSalaryDisbursementBatch
- UnitsDataTable
- PermissionsDataTable
- input.tsx
- Company
- Yajra\DataTables\EloquentDataTable
- EmployeesDataTable
- PayrollEmployeeSalaryProfilesDataTable
- ProfileController.php
- ApprovalFlowsDataTable
- ProductController.php
- BranchesDataTable
- DepartmentsDataTable
- DesignationsDataTable
- DivisionsDataTable
- PositionsDataTable
- PayrollSalaryHeadsDataTable
- Product
- UsersDataTable
- LoginRequest
- sidebar.tsx
- MigrationHelper
- create.tsx
- dropdown-menu.tsx
- DepartmentController
- app-header.tsx
- Category
- CoreService
- UpdateUserRequest
- Controller
- PayrollSalaryDisbursementBatchEmployee.php
- Illuminate\Notifications\DatabaseNotification
- utils.ts
- PayrollEmployeeSalaryProfile
- app-sidebar-layout.tsx
- use-appearance.tsx
- employee-salary-profiles/show.tsx
- salary-disbursement-batches/index.tsx
- UserController
- app-layout.tsx
- positions/index.tsx
- PayrollSalaryDisbursementBatchesDataTable
- notification/index.tsx
- index.d.ts
- HrbpsDataTable
- PositionGroupsDataTable
- toggle-group.tsx
- use-debounced-search.ts
- ui/icon.tsx
- fonts.ts

## God Nodes (most connected - your core abstractions)
1. `cn()` - 174 edges
2. `Controller` - 81 edges
3. `CoreService` - 66 edges
4. `Button()` - 58 edges
5. `BaseDataTable` - 51 edges
6. `Input()` - 41 edges
7. `Label()` - 37 edges
8. `breadcrumbItems` - 33 edges
9. `User` - 25 edges
10. `BaseDialog()` - 25 edges

## Surprising Connections (you probably didn't know these)
- `CompanyController` --references--> `CompanyService`  [EXTRACTED]
  Http/Controllers/Configuration/Company/CompanyController.php → Services/Configuration/Company/CompanyService.php
- `DepartmentController` --references--> `DepartmentService`  [EXTRACTED]
  Http/Controllers/Configuration/Department/DepartmentController.php → Services/Configuration/Department/DepartmentService.php
- `DesignationController` --references--> `DesignationService`  [EXTRACTED]
  Http/Controllers/Configuration/Designation/DesignationController.php → Services/Configuration/Designation/DesignationService.php
- `DivisionController` --references--> `DivisionService`  [EXTRACTED]
  Http/Controllers/Configuration/Division/DivisionController.php → Services/Configuration/Division/DivisionService.php
- `EmployeeController` --references--> `DepartmentService`  [EXTRACTED]
  Http/Controllers/Employee/Employee/EmployeeController.php → Services/Configuration/Department/DepartmentService.php

## Import Cycles
- None detected.

## Communities (104 total, 29 thin omitted)

### Community 0 - "salary-structures/index.tsx"
Cohesion: 0.08
Nodes (34): Select(), SelectContent(), SelectItem(), SelectTrigger(), SelectValue(), breadcrumbItems, breadcrumbs, TypeOption (+26 more)

### Community 1 - "command.tsx"
Cohesion: 0.06
Nodes (50): CommandMenu(), filterSidebarItemsByPermission(), DatePicker(), DatePickerProps, formatLocalDateToYYYYMMDD(), parseYYYYMMDDToLocalDate(), HeadingSmall(), MultiCombobox() (+42 more)

### Community 2 - "roles/edit.tsx"
Cohesion: 0.10
Nodes (22): AppLogo(), AppLogoIcon(), Card(), CardContent(), CardDescription(), CardFooter(), CardHeader(), CardTitle() (+14 more)

### Community 3 - "PayrollSalaryHeadController.php"
Cohesion: 0.09
Nodes (12): label(), options(), label(), options(), label(), options(), label(), options() (+4 more)

### Community 4 - "data-table.tsx"
Cohesion: 0.07
Nodes (38): ColumnDef, DataTable, DataTableProps, TopProgressBarProps, Table(), TableBody(), TableCell(), TableHead() (+30 more)

### Community 5 - "cn"
Cohesion: 0.09
Nodes (32): Alert(), AlertDescription(), AlertTitle(), alertVariants, Breadcrumb(), BreadcrumbEllipsis(), BreadcrumbItem(), BreadcrumbLink() (+24 more)

### Community 6 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.10
Nodes (18): App\Traits\HasUlid, Illuminate\Database\Eloquent\Model, ApprovalFlow, ApprovalFlowGroupItem, ApprovalLevel, Branch, Department, Designation (+10 more)

### Community 7 - "ApprovalLevelController"
Cohesion: 0.10
Nodes (6): label(), options(), ApprovalLevelController, StoreApprovalLevelRequest, UpdateApprovalLevelRequest, ApprovalLevelService

### Community 8 - "app-sidebar.tsx"
Cohesion: 0.12
Nodes (21): AppCompany(), AppSidebar(), CompanySwitcher(), filterSidebarItemsByPermission(), SidebarCollapsibleItem(), Collapsible(), CollapsibleContent(), CollapsibleTrigger() (+13 more)

### Community 9 - "ApprovalFlowGroup"
Cohesion: 0.11
Nodes (5): ApprovalFlowGroupController, StoreApprovalFlowGroupRequest, UpdateApprovalFlowGroupRequest, ApprovalFlowGroup, ApprovalFlowGroupService

### Community 10 - "PayrollSalaryStructureController"
Cohesion: 0.12
Nodes (4): PayrollSalaryStructureController, StorePayrollSalaryStructureRequest, UpdatePayrollSalaryStructureRequest, PayrollSalaryStructureService

### Community 11 - "bulk-delete-button.tsx"
Cohesion: 0.25
Nodes (12): BulkDeleteButtonProps, DeleteConfirmDialogProps, AlertDialog(), AlertDialogAction(), AlertDialogCancel(), AlertDialogContent(), AlertDialogDescription(), AlertDialogFooter() (+4 more)

### Community 12 - "CategoryController"
Cohesion: 0.11
Nodes (4): CategoryController, StoreCategoryRequest, UpdateCategoryRequest, CategoryService

### Community 13 - "HrbpController"
Cohesion: 0.11
Nodes (4): HrbpController, StoreHrbpRequest, UpdateHrbpRequest, HrbpService

### Community 14 - "PositionGroupController"
Cohesion: 0.11
Nodes (4): PositionGroupController, StorePositionGroupRequest, UpdatePositionGroupRequest, PositionGroupService

### Community 15 - "UnitController"
Cohesion: 0.12
Nodes (4): UnitController, StoreUnitRequest, DepartmentService, UnitService

### Community 16 - "BrandController"
Cohesion: 0.11
Nodes (4): BrandController, StoreBrandRequest, UpdateBrandRequest, BrandService

### Community 17 - "UnitController"
Cohesion: 0.11
Nodes (4): UnitController, StoreUnitRequest, UpdateUnitRequest, UnitService

### Community 18 - "dashboard.tsx"
Cohesion: 0.40
Nodes (3): PlaceholderPattern(), PlaceholderPatternProps, breadcrumbs

### Community 19 - "Illuminate\Http\Request"
Cohesion: 0.11
Nodes (10): NotificationController, CompanyResource, DepartmentResource, DivisionResource, CategoryResource, PermissionResource, RoleResource, UserResource (+2 more)

### Community 20 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.13
Nodes (4): BrandsDataTable, CategoriesDataTable, ProductsDataTable, Illuminate\Database\Eloquent\Builder

### Community 21 - "DesignationController"
Cohesion: 0.12
Nodes (3): DesignationController, StoreDesignationRequest, UpdateDesignationRequest

### Community 22 - "DivisionController"
Cohesion: 0.13
Nodes (3): DivisionController, StoreDivisionRequest, UpdateDivisionRequest

### Community 23 - "EmployeeContactController"
Cohesion: 0.15
Nodes (4): EmployeeContactController, StoreEmployeeContactRequest, UpdateEmployeeContactRequest, EmployeeContactService

### Community 24 - "EmployeeDocumentController"
Cohesion: 0.15
Nodes (4): EmployeeDocumentController, StoreEmployeeDocumentRequest, UpdateEmployeeDocumentRequest, EmployeeDocumentService

### Community 25 - "EmployeeEducationController"
Cohesion: 0.15
Nodes (4): EmployeeEducationController, StoreEmployeeEducationRequest, UpdateEmployeeEducationRequest, EmployeeEducationService

### Community 26 - "EmployeeExperienceController"
Cohesion: 0.15
Nodes (4): EmployeeExperienceController, StoreEmployeeExperienceRequest, UpdateEmployeeExperienceRequest, EmployeeExperienceService

### Community 27 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.09
Nodes (7): StorePositionRequest, UpdatePositionRequest, UpdateUnitRequest, StoreRoleRequest, UpdateRoleRequest, StoreUserRequest, Illuminate\Foundation\Http\FormRequest

### Community 28 - "EmployeeController.php"
Cohesion: 0.17
Nodes (6): label(), options(), label(), options(), StoreEmployeeRequest, UpdateEmployeeRequest

### Community 29 - "Employee"
Cohesion: 0.12
Nodes (6): Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\HasMany, Employee, PayrollEmployeeSalaryProfileItem, PayrollSalaryDisbursementBatchEmployeeItem, PayrollSalaryStructure

### Community 30 - "TenancyServiceProvider"
Cohesion: 0.20
Nodes (4): Illuminate\Support\ServiceProvider, AppServiceProvider, TenancyServiceProvider, Stancl\Tenancy\Middleware

### Community 31 - "User"
Cohesion: 0.13
Nodes (7): ImpersonateController, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, User, UserService, Spatie\Permission\Traits\HasRoles

### Community 32 - "RoleController"
Cohesion: 0.11
Nodes (5): RolesDataTable, RoleController, Role, RoleService, Spatie\Permission\Models\Role

### Community 33 - "BranchController"
Cohesion: 0.09
Nodes (5): BranchController, StoreBranchRequest, UpdateBranchRequest, BranchService, CompanyService

### Community 34 - "BaseDataTable"
Cohesion: 0.19
Nodes (4): BaseDataTable, ApprovalLevelsDataTable, Illuminate\Support\Collection, Yajra\DataTables\Services\DataTable

### Community 35 - "ApprovalFlowTypeEnum.php"
Cohesion: 0.18
Nodes (4): label(), options(), StoreApprovalFlowRequest, UpdateApprovalFlowRequest

### Community 37 - "CompanyController"
Cohesion: 0.14
Nodes (3): CompanyController, StoreCompanyRequest, UpdateCompanyRequest

### Community 40 - "Closure"
Cohesion: 0.33
Nodes (5): Closure, CompaniesPermission, HandleAppearance, HandleTenancyFromSession, Symfony\Component\HttpFoundation\Response

### Community 43 - "button.tsx"
Cohesion: 0.10
Nodes (23): RowActions(), RowActionsProps, BaseDialog(), BaseDialogProps, DeleteConfirmDialog(), Button(), buttonVariants, Calendar() (+15 more)

### Community 44 - "PayrollSalaryDisbursementBatch"
Cohesion: 0.07
Nodes (11): label(), options(), label(), options(), PayrollSalaryDisbursementBatchController, Request, DisbursePayrollSalaryDisbursementBatchRequest, StorePayrollSalaryDisbursementBatchRequest (+3 more)

### Community 46 - "PermissionsDataTable"
Cohesion: 0.10
Nodes (7): PermissionsDataTable, PermissionController, StorePermissionRequest, UpdatePermissionRequest, Permission, PermissionService, Spatie\Permission\Models\Permission

### Community 47 - "input.tsx"
Cohesion: 0.11
Nodes (17): InputError(), LinkProps, TextLink(), Input(), Label(), UserCombobox(), AuthLayout(), LoginForm (+9 more)

### Community 49 - "Yajra\DataTables\EloquentDataTable"
Cohesion: 0.16
Nodes (3): PayrollSalaryStructuresDataTable, UnitsDataTable, Yajra\DataTables\EloquentDataTable

### Community 54 - "ProductController.php"
Cohesion: 0.13
Nodes (14): label(), options(), label(), options(), label(), options(), label(), options() (+6 more)

### Community 61 - "Product"
Cohesion: 0.10
Nodes (4): ProductController, Product, ProductItem, ProductService

### Community 64 - "sidebar.tsx"
Cohesion: 0.10
Nodes (23): Sheet(), SheetContent(), SheetDescription(), SheetFooter(), SheetHeader(), SheetOverlay(), SheetTitle(), SheetTrigger() (+15 more)

### Community 66 - "create.tsx"
Cohesion: 0.08
Nodes (20): Checkbox(), breadcrumbs, defaultForm, EnumOption, Props, breadcrumbs, ComboItem, emptyForm (+12 more)

### Community 67 - "dropdown-menu.tsx"
Cohesion: 0.11
Nodes (16): Notification(), NotificationItem, PaginatedResponse, Badge(), badgeVariants, DropdownMenuCheckboxItem(), DropdownMenuGroup(), DropdownMenuItem() (+8 more)

### Community 68 - "DepartmentController"
Cohesion: 0.11
Nodes (4): DepartmentController, StoreDepartmentRequest, UpdateDepartmentRequest, DivisionService

### Community 69 - "app-header.tsx"
Cohesion: 0.17
Nodes (16): AppHeader(), AppHeaderProps, mainNavItems, rightNavItems, Icon(), IconProps, NavUser(), Avatar() (+8 more)

### Community 70 - "Category"
Cohesion: 0.18
Nodes (6): Illuminate\Database\Eloquent\Factories\HasFactory, Brand, Category, Unit, Spatie\MediaLibrary\HasMedia, Spatie\MediaLibrary\InteractsWithMedia

### Community 71 - "CoreService"
Cohesion: 0.17
Nodes (4): Illuminate\Database\Eloquent\Collection, DesignationService, CoreService, Stancl\Tenancy\Database\Models\Tenant

### Community 73 - "Controller"
Cohesion: 0.11
Nodes (13): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordResetLinkController, RegisteredUserController, VerifyEmailController (+5 more)

### Community 74 - "PayrollSalaryDisbursementBatchEmployee.php"
Cohesion: 0.19
Nodes (5): label(), options(), label(), options(), PayrollSalaryDisbursementBatchEmployee

### Community 77 - "utils.ts"
Cohesion: 0.17
Nodes (10): AppSidebarHeader(), HeaderProps, Heading(), Search(), SearchProps, Separator, SidebarTrigger(), Skeleton() (+2 more)

### Community 78 - "PayrollEmployeeSalaryProfile"
Cohesion: 0.21
Nodes (6): label(), options(), Illuminate\Database\Eloquent\Relations\HasOne, PayrollEmployeeSalaryProfile, PayrollSalaryHead, PayrollEmployeeSalaryProfileService

### Community 79 - "app-sidebar-layout.tsx"
Cohesion: 0.19
Nodes (9): AppContent(), AppContentProps, AppShell(), AppShellProps, Breadcrumbs(), ImpersonationBanner(), SidebarInset(), SidebarProvider() (+1 more)

### Community 80 - "use-appearance.tsx"
Cohesion: 0.29
Nodes (10): AppearanceToggleDropdown(), AppearanceToggleTab(), Appearance, applyTheme(), handleSystemThemeChange(), initializeTheme(), mediaQuery(), prefersDark() (+2 more)

### Community 81 - "employee-salary-profiles/show.tsx"
Cohesion: 0.22
Nodes (12): ActiveProfile, calcTotals(), DEDUCTION_CATEGORIES, Employee, getCategoryColor(), getCategoryLabel(), GROSS_CATEGORIES, Props (+4 more)

### Community 82 - "salary-disbursement-batches/index.tsx"
Cohesion: 0.22
Nodes (9): Textarea, breadcrumbs, currentYear, defaultForm, Index(), MONTHS, STATUS_COLORS, TYPE_COLORS (+1 more)

### Community 84 - "app-layout.tsx"
Cohesion: 0.28
Nodes (6): Toaster(), AppLayoutProps, getIcon(), NotificationItem, Props, Show()

### Community 85 - "positions/index.tsx"
Cohesion: 0.25
Nodes (8): Branch, breadcrumbs, Department, Division, getDescendantIds(), Index(), PositionGroup, PositionOption

### Community 87 - "notification/index.tsx"
Cohesion: 0.29
Nodes (7): breadcrumbs, getIcon(), Index(), NotificationItem, PaginatedNotifications, PaginationLink, Props

### Community 88 - "index.d.ts"
Cohesion: 0.25
Nodes (7): Auth, BreadcrumbItem, Company, NavGroup, NavItem, SharedData, User

### Community 91 - "toggle-group.tsx"
Cohesion: 0.43
Nodes (5): ToggleGroup(), ToggleGroupContext, ToggleGroupItem(), Toggle(), toggleVariants

## Knowledge Gaps
- **181 isolated node(s):** `AppContentProps`, `mainNavItems`, `rightNavItems`, `AppHeaderProps`, `AppShellProps` (+176 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **29 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `PayrollSalaryHeadController.php`, `Illuminate\Database\Eloquent\Model`, `ApprovalLevelController`, `ApprovalFlowGroup`, `PayrollSalaryStructureController`, `CategoryController`, `HrbpController`, `PositionGroupController`, `UnitController`, `BrandController`, `UnitController`, `Illuminate\Http\Request`, `DesignationController`, `DivisionController`, `EmployeeContactController`, `EmployeeDocumentController`, `EmployeeEducationController`, `EmployeeExperienceController`, `EmployeeController.php`, `User`, `RoleController`, `BranchController`, `CompanyController`, `EmployeeController`, `PayrollEmployeeSalaryProfileController`, `ApprovalFlowController`, `PositionController`, `PayrollSalaryDisbursementBatch`, `PermissionsDataTable`, `ProfileController.php`, `ProductController.php`, `Product`, `DepartmentController`, `UserController`?**
  _High betweenness centrality (0.124) - this node is a cross-community bridge._
- **Why does `cn()` connect `cn` to `salary-structures/index.tsx`, `command.tsx`, `roles/edit.tsx`, `dropdown-menu.tsx`, `create.tsx`, `app-header.tsx`, `sidebar.tsx`, `data-table.tsx`, `app-sidebar.tsx`, `bulk-delete-button.tsx`, `button.tsx`, `utils.ts`, `input.tsx`, `use-appearance.tsx`, `app-sidebar-layout.tsx`, `salary-disbursement-batches/index.tsx`, `positions/index.tsx`, `toggle-group.tsx`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Why does `BaseDataTable` connect `BaseDataTable` to `Illuminate\Database\Eloquent\Builder`, `RoleController`, `UnitsDataTable`, `PermissionsDataTable`, `Company`, `Yajra\DataTables\EloquentDataTable`, `EmployeesDataTable`, `PayrollEmployeeSalaryProfilesDataTable`, `ApprovalFlowsDataTable`, `BranchesDataTable`, `DepartmentsDataTable`, `DesignationsDataTable`, `DivisionsDataTable`, `PositionsDataTable`, `PayrollSalaryHeadsDataTable`, `UsersDataTable`, `Category`, `PayrollSalaryDisbursementBatchesDataTable`, `HrbpsDataTable`, `PositionGroupsDataTable`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **What connects `AppContentProps`, `mainNavItems`, `rightNavItems` to the rest of the system?**
  _181 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `salary-structures/index.tsx` be split into smaller, more focused modules?**
  _Cohesion score 0.07662337662337662 - nodes in this community are weakly interconnected._
- **Should `command.tsx` be split into smaller, more focused modules?**
  _Cohesion score 0.05575065847234416 - nodes in this community are weakly interconnected._
- **Should `roles/edit.tsx` be split into smaller, more focused modules?**
  _Cohesion score 0.0989247311827957 - nodes in this community are weakly interconnected._