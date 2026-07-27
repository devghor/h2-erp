import { SectionHeader } from '@/components/dashboard/section-header';
import { Sparkline } from '@/components/dashboard/sparkline';
import { StatCard } from '@/components/dashboard/stat-card';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import {
    ArrowUpRight,
    Briefcase,
    Building2,
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
    Plus,
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

function formatDate() {
    return new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(new Date());
}

export default function Dashboard({ employee, organization, user, leave }: DashboardStats) {
    const hasLeaveTrends = leave.leave_trends_monthly.length > 0;
    const lastLeaveTrend = hasLeaveTrends ? leave.leave_trends_monthly[leave.leave_trends_monthly.length - 1] : 0;
    const previousLeaveTrend =
        hasLeaveTrends && leave.leave_trends_monthly.length > 1 ? leave.leave_trends_monthly[leave.leave_trends_monthly.length - 2] : 0;
    const leaveTrendDirection = lastLeaveTrend > previousLeaveTrend ? 'up' : lastLeaveTrend < previousLeaveTrend ? 'down' : 'neutral';

    return (
        <AppLayout title="">
            <div className="flex flex-col gap-8 p-4 sm:p-6 lg:p-8">
                {/* Header */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div className="flex flex-col gap-1">
                        <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">Dashboard</h1>
                        <p className="text-sm text-muted-foreground">{formatDate()}</p>
                    </div>
                    <div className="flex items-center gap-2">
                        <Button variant="outline" size="sm">
                            View Reports
                            <ArrowUpRight data-icon="inline-end" className="size-4" />
                        </Button>
                        <Button size="sm">
                            <Plus data-icon="inline-start" className="size-4" />
                            Quick Action
                        </Button>
                    </div>
                </div>

                {/* Employee Section */}
                <section className="flex flex-col gap-4">
                    <SectionHeader title="Employees" description="Workforce overview and status" icon={Users} />
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                        <StatCard
                            title="Total Employees"
                            value={employee.total_employees}
                            icon={Users}
                            description="Across all companies"
                            accent="blue"
                            trend={employee.new_joiners_this_month > 0 ? 'up' : 'neutral'}
                            trendLabel={employee.new_joiners_this_month > 0 ? `${employee.new_joiners_this_month} new this month` : 'No new joiners'}
                        />
                        <StatCard
                            title="Active Employees"
                            value={employee.active_employees}
                            icon={UserCheck}
                            description="Currently active"
                            accent="emerald"
                        />
                        <StatCard
                            title="New Joiners"
                            value={employee.new_joiners_this_month}
                            icon={UserPlus}
                            description="This month"
                            accent="blue"
                        />
                        <StatCard
                            title="On Probation"
                            value={employee.employees_on_probation}
                            icon={Clock}
                            description="Awaiting confirmation"
                            accent="amber"
                        />
                        <StatCard
                            title="Pending Confirmations"
                            value={employee.pending_confirmations}
                            icon={ListChecks}
                            description="Require action"
                            accent="rose"
                        />
                    </div>
                </section>

                <Separator />

                {/* Organization & Users */}
                <div className="grid gap-8 xl:grid-cols-3">
                    {/* Organization Section */}
                    <section className="xl:col-span-2">
                        <div className="flex flex-col gap-4">
                            <SectionHeader title="Organization" description="Structure and open positions" icon={Building2} />
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <StatCard
                                    title="Departments"
                                    value={organization.total_departments}
                                    icon={LayoutGrid}
                                    description="Functional units"
                                    accent="violet"
                                />
                                <StatCard
                                    title="Branches"
                                    value={organization.total_branches}
                                    icon={MapPin}
                                    description="Office locations"
                                    accent="violet"
                                />
                                <StatCard
                                    title="Companies"
                                    value={organization.total_companies}
                                    icon={Globe}
                                    description="Registered entities"
                                    accent="violet"
                                />
                                <StatCard
                                    title="Open Positions"
                                    value={organization.open_positions}
                                    icon={Briefcase}
                                    description="Hiring pipeline"
                                    accent="amber"
                                    trend={organization.open_positions > 0 ? 'up' : 'neutral'}
                                    trendLabel={organization.open_positions > 0 ? 'Active hiring' : 'No openings'}
                                />
                            </div>
                        </div>
                    </section>

                    {/* Users Section */}
                    <section>
                        <div className="flex flex-col gap-4">
                            <SectionHeader title="Users" description="System access status" icon={UserX} />
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-1">
                                <StatCard title="Total Users" value={user.total_users} icon={UserX} description="Registered accounts" accent="blue" />
                                <StatCard
                                    title="Active Users"
                                    value={user.active_users}
                                    icon={ShieldCheck}
                                    description="Currently enabled"
                                    accent="emerald"
                                    trend={user.active_users > 0 ? 'up' : 'neutral'}
                                    trendLabel={user.active_users > 0 ? 'Operational' : 'No active users'}
                                />
                            </div>
                        </div>
                    </section>
                </div>

                <Separator />

                {/* Leave Management Section */}
                <section className="flex flex-col gap-4">
                    <SectionHeader title="Leave Management" description="Requests, balances, and trends" icon={CalendarDays} />
                    <div className="grid gap-4 lg:grid-cols-3">
                        <div className="lg:col-span-2">
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <StatCard
                                    title="Pending Requests"
                                    value={leave.pending_leave_requests}
                                    icon={FileText}
                                    description="Awaiting approval"
                                    accent="amber"
                                    trend={leave.pending_leave_requests > 0 ? 'up' : 'neutral'}
                                    trendLabel={leave.pending_leave_requests > 0 ? 'Needs review' : 'All caught up'}
                                />
                                <StatCard
                                    title="Today's Leave"
                                    value={leave.todays_leave}
                                    icon={CalendarDays}
                                    description="Away today"
                                    accent="blue"
                                />
                                <StatCard
                                    title="Upcoming Leaves"
                                    value={leave.upcoming_leaves}
                                    icon={CalendarPlus}
                                    description="Scheduled ahead"
                                    accent="violet"
                                />
                                <StatCard
                                    title="Leave Balance"
                                    value={leave.leave_balance_summary}
                                    icon={CircleDollarSign}
                                    description="Combined days remaining"
                                    accent="emerald"
                                />
                                <StatCard
                                    title="Approval Rate"
                                    value={leave.leave_approval_statistics}
                                    icon={CheckCircle2}
                                    description="Approved vs total"
                                    accent="emerald"
                                />
                                <StatCard
                                    title="Employees on Leave"
                                    value={leave.employees_on_leave}
                                    icon={CalendarOff}
                                    description="Currently on leave"
                                    accent="rose"
                                />
                            </div>
                        </div>

                        {/* Leave Trends Chart */}
                        <Card className="flex flex-col">
                            <CardHeader>
                                <div className="flex items-start justify-between">
                                    <div className="flex flex-col gap-1">
                                        <CardTitle className="text-base">Leave Trends</CardTitle>
                                        <CardDescription>Monthly leave requests</CardDescription>
                                    </div>
                                    <div className="flex size-8 items-center justify-center rounded-md bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        <TrendingUp className="size-4" />
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent className="flex flex-1 flex-col justify-between gap-4">
                                {hasLeaveTrends ? (
                                    <>
                                        <div className="flex items-baseline gap-2">
                                            <span className="text-3xl font-bold tracking-tight">{lastLeaveTrend}</span>
                                            <span className="text-xs text-muted-foreground">this month</span>
                                        </div>
                                        <div className="h-24 w-full sm:h-32">
                                            <Sparkline
                                                data={leave.leave_trends_monthly}
                                                strokeClassName="stroke-emerald-500 dark:stroke-emerald-400"
                                                fillClassName="fill-emerald-500/10 dark:fill-emerald-400/10"
                                            />
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <span
                                                className={
                                                    leaveTrendDirection === 'up'
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : leaveTrendDirection === 'down'
                                                          ? 'text-rose-600 dark:text-rose-400'
                                                          : 'text-muted-foreground'
                                                }
                                            >
                                                {leaveTrendDirection === 'up' ? '↑' : leaveTrendDirection === 'down' ? '↓' : '—'}
                                            </span>
                                            <span className="text-xs text-muted-foreground">
                                                {leaveTrendDirection === 'up'
                                                    ? 'Increasing from last month'
                                                    : leaveTrendDirection === 'down'
                                                      ? 'Decreasing from last month'
                                                      : 'Stable from last month'}
                                            </span>
                                        </div>
                                    </>
                                ) : (
                                    <div className="flex flex-1 flex-col items-center justify-center gap-2 text-muted-foreground">
                                        <TrendingUp className="size-8 opacity-40" />
                                        <p className="text-sm">No leave trend data available</p>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>
                </section>
            </div>
        </AppLayout>
    );
}
