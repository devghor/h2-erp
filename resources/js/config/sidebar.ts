import {
    Award,
    Banknote,
    Briefcase,
    Building,
    Building2,
    Calendar,
    Clock,
    Contact,
    DollarSign,
    FileSpreadsheet,
    IdCard,
    Key,
    Landmark,
    Layers,
    LayoutDashboard,
    ListChecks,
    Lock,
    MonitorCog,
    Package,
    Ruler,
    Tag,
    UserCog,
    UserRound,
    Users,
    Workflow,
} from 'lucide-react';

type BaseNavItem = {
    title: string;
    badge?: string;
    icon?: React.ElementType;
    can?: string;
};

type NavLink = BaseNavItem & {
    url: any['to'] | (string & {});
    items?: never;
};

type NavCollapsible = BaseNavItem & {
    items: (BaseNavItem & { url: any['to'] | (string & {}) })[];
    url?: never;
};

type NavItem = NavCollapsible | NavLink;

type NavGroup = {
    title: string;
    items: NavItem[];
    can?: string;
};

type SidebarData = {
    navGroups: NavGroup[];
};

export const sidebarData: SidebarData = {
    navGroups: [
        {
            title: 'General',
            can: 'READ_GENERAL',
            items: [
                {
                    title: 'Dashboard',
                    url: '/dashboard',
                    icon: LayoutDashboard,
                    can: 'READ_GENERAL_DASHBOARD',
                },
            ],
        },
        {
            title: 'Uam',
            can: 'READ_UAM',
            items: [
                {
                    title: 'Users',
                    url: '/uam/users',
                    icon: Users,
                    can: 'READ_UAM_USER',
                },
                {
                    title: 'Roles',
                    url: '/uam/roles',
                    icon: Lock,
                    can: 'READ_UAM_ROLE',
                },
                {
                    title: 'Permissions',
                    url: '/uam/permissions',
                    icon: Key,
                    can: 'READ_UAM_PERMISSION',
                },
            ],
        },
        {
            title: 'Configuration',
            can: 'READ_CONFIGURATION',
            items: [
                {
                    title: 'Companies',
                    url: '/configuration/companies',
                    icon: Landmark,
                    can: 'READ_CONFIGURATION_COMPANY',
                },
                {
                    title: 'Branches',
                    url: '/configuration/branches',
                    icon: Building2,
                    can: 'READ_CONFIGURATION_BRANCH',
                },
                {
                    title: 'Divisions',
                    url: '/configuration/divisions',
                    icon: Building,
                    can: 'READ_CONFIGURATION_DIVISION',
                },
                {
                    title: 'Departments',
                    url: '/configuration/departments',
                    icon: Briefcase,
                    can: 'READ_CONFIGURATION_DEPARTMENT',
                },
                {
                    title: 'Units',
                    url: '/configuration/units',
                    icon: Briefcase,
                    can: 'READ_CONFIGURATION_UNIT',
                },
                {
                    title: 'Designations',
                    url: '/configuration/designations',
                    icon: IdCard,
                    can: 'READ_CONFIGURATION_DESIGNATION',
                },
                {
                    title: 'Position Groups',
                    url: '/configuration/position-groups',
                    icon: Layers,
                    can: 'READ_CONFIGURATION_POSITION_GROUP',
                },
                {
                    title: 'Positions',
                    url: '/configuration/positions',
                    icon: MonitorCog,
                    can: 'READ_CONFIGURATION_POSITION',
                },
                {
                    title: 'HRBP',
                    url: '/configuration/hrbps',
                    icon: Contact,
                    can: 'READ_CONFIGURATION_HRBP',
                },
                {
                    title: 'Shifts',
                    url: '/configuration/shifts',
                    icon: Clock,
                    can: 'READ_CONFIGURATION_SHIFT',
                },
                {
                    title: 'Approval Levels',
                    url: '/configuration/approval-levels',
                    icon: ListChecks,
                    can: 'READ_CONFIGURATION_APPROVAL_LEVEL',
                },
                {
                    title: 'Approval Flows',
                    url: '/configuration/approval-flows',
                    icon: Workflow,
                    can: 'READ_CONFIGURATION_APPROVAL_FLOW',
                },
            ],
        },
        {
            title: 'Product',
            can: 'READ_PRODUCT',
            items: [
                {
                    title: 'Categories',
                    url: '/product/categories',
                    icon: Tag,
                    can: 'READ_PRODUCT_CATEGORY',
                },
                {
                    title: 'Brands',
                    url: '/product/brands',
                    icon: Award,
                    can: 'READ_PRODUCT_BRAND',
                },
                {
                    title: 'Units',
                    url: '/product/units',
                    icon: Ruler,
                    can: 'READ_PRODUCT_UNIT',
                },
                {
                    title: 'Products',
                    url: '/product/products',
                    icon: Package,
                    can: 'READ_PRODUCT_PRODUCT',
                },
            ],
        },
        {
            title: 'Employee',
            can: 'READ_EMPLOYEE',
            items: [
                {
                    title: 'Employees',
                    url: '/employee/employees',
                    icon: UserRound,
                    can: 'READ_EMPLOYEE_EMPLOYEE',
                },
            ],
        },
        {
            title: 'Leave',
            can: 'READ_LEAVE',
            items: [
                {
                    title: 'Leave Types',
                    url: '/leave/leave-types',
                    icon: Calendar,
                    can: 'READ_LEAVE_LEAVE_TYPE',
                },
            ],
        },
        {
            title: 'Payroll',
            can: 'READ_PAYROLL',
            items: [
                {
                    title: 'Salary Heads',
                    url: '/payroll/salary-heads',
                    icon: DollarSign,
                    can: 'READ_PAYROLL_SALARY_HEAD',
                },
                {
                    title: 'Salary Structures',
                    url: '/payroll/salary-structures',
                    icon: FileSpreadsheet,
                    can: 'READ_PAYROLL_SALARY_STRUCTURE',
                },
                {
                    title: 'Employee Salary Profiles',
                    url: '/payroll/employee-salary-profiles',
                    icon: UserCog,
                    can: 'READ_PAYROLL_EMPLOYEE_SALARY_PROFILE',
                },
                {
                    title: 'Salary Disbursement',
                    url: '/payroll/salary-disbursement-batches',
                    icon: Banknote,
                    can: 'READ_PAYROLL_SALARY_DISBURSEMENT_BATCH',
                },
            ],
        },
    ],
};
