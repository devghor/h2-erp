import { SummaryCard } from '@/components/dashboard/summary-card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import {
    Briefcase,
    CalendarDays,
    CalendarOff,
    CalendarPlus,
    CheckCircle2,
    CircleDollarSign,
    Clock,
    FileText,
    Globe,
    LayoutGrid,
    ListChecks,
    MapPin,
    ShieldCheck,
    TrendingUp,
    UserCheck,
    UserPlus,
    Users,
    UserX,
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardStats {
    employee: {
        total_employees: number;
        active_employees: number;
        new_joiners_this_month: number;
        employees_on_probation: number;
        pending_confirmations: number;
    };
    organization: {
        total_departments: number;
        total_branches: number;
        total_companies: number;
        open_positions: number;
    };
    user: {
        total_users: number;
        active_users: number;
    };
    leave: {
        pending_leave_requests: number;
        todays_leave: number;
        upcoming_leaves: number;
        leave_balance_summary: number;
        leave_approval_statistics: number;
        employees_on_leave: number;
        leave_trends_monthly: number[];
    };
}

export default function Dashboard({ employee, organization, user, leave }: DashboardStats) {
    return (
        <AppLayout title="Dashboard" breadcrumbs={breadcrumbs}>
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                {/* Employee Section */}
                <div>
                    <h2 className="text-lg font-semibold tracking-tight">Employees</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                        <SummaryCard title="Total Employees" value={employee.total_employees} icon={Users} />
                        <SummaryCard title="Active Employees" value={employee.active_employees} icon={UserCheck} />
                        <SummaryCard title="New Joiners (This Month)" value={employee.new_joiners_this_month} icon={UserPlus} />
                        <SummaryCard title="Employees on Probation" value={employee.employees_on_probation} icon={Clock} />
                        <SummaryCard title="Pending Confirmations" value={employee.pending_confirmations} icon={ListChecks} />
                    </div>
                </div>

                {/* Organization Section */}
                <div>
                    <h2 className="text-lg font-semibold tracking-tight">Organization</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4">
                        <SummaryCard title="Total Departments" value={organization.total_departments} icon={LayoutGrid} />
                        <SummaryCard title="Total Branches" value={organization.total_branches} icon={MapPin} />
                        <SummaryCard title="Total Companies" value={organization.total_companies} icon={Globe} />
                        <SummaryCard title="Open Positions" value={organization.open_positions} icon={Briefcase} />
                    </div>
                </div>

                {/* Users Section */}
                <div>
                    <h2 className="text-lg font-semibold tracking-tight">Users</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
                        <SummaryCard title="Total Users" value={user.total_users} icon={UserX} />
                        <SummaryCard title="Active Users" value={user.active_users} icon={ShieldCheck} />
                    </div>
                </div>

                {/* Leave Management Section */}
                <div>
                    <h2 className="text-lg font-semibold tracking-tight">Leave Management</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7">
                        <SummaryCard title="Pending Leave Requests" value={leave.pending_leave_requests} icon={FileText} />
                        <SummaryCard title="Today's Leave" value={leave.todays_leave} icon={CalendarDays} />
                        <SummaryCard title="Upcoming Leaves" value={leave.upcoming_leaves} icon={CalendarPlus} />
                        <SummaryCard title="Leave Balance Summary" value={leave.leave_balance_summary} icon={CircleDollarSign} />
                        <SummaryCard title="Leave Approval Statistics" value={leave.leave_approval_statistics} icon={CheckCircle2} />
                        <SummaryCard title="Employees on Leave" value={leave.employees_on_leave} icon={CalendarOff} />
                        <SummaryCard
                            title="Leave Trends (Monthly)"
                            value={leave.leave_trends_monthly.length > 0 ? leave.leave_trends_monthly.join(', ') : 'N/A'}
                            icon={TrendingUp}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
