<?php

namespace App\Services\Dashboard;

use App\Enums\Employee\EmployeeStatusEnum;
use App\Models\Configuration\Branch\Branch;
use App\Models\Configuration\Company\Company;
use App\Models\Configuration\Department\Department;
use App\Models\Configuration\Position\Position;
use App\Models\Employee\Employee\Employee;
use App\Models\Uam\User;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $employeeQuery = Employee::query();
        $userQuery = User::query();

        $totalEmployees = $employeeQuery->count();

        $activeEmployees = (clone $employeeQuery)
            ->where('employment_status', 'Active')
            ->count();

        $newJoinersThisMonth = (clone $employeeQuery)
            ->whereBetween('hire_date', [$startOfMonth, $endOfMonth])
            ->count();

        $employeesOnProbation = (clone $employeeQuery)
            ->where('employee_status', EmployeeStatusEnum::OnProbation)
            ->count();

        $pendingConfirmations = (clone $employeeQuery)
            ->where('employee_status', EmployeeStatusEnum::OnProbation)
            ->count();

        $totalDepartments = Department::count();
        $totalBranches = Branch::count();
        $totalCompanies = Company::count();

        $openPositions = Position::count();

        $totalUsers = $userQuery->count();

        $activeUsers = (clone $userQuery)
            ->whereNotNull('email_verified_at')
            ->count();

        return [
            'employee' => [
                'total_employees'         => $totalEmployees,
                'active_employees'        => $activeEmployees,
                'new_joiners_this_month'  => $newJoinersThisMonth,
                'employees_on_probation'  => $employeesOnProbation,
                'pending_confirmations'   => $pendingConfirmations,
            ],
            'organization' => [
                'total_departments' => $totalDepartments,
                'total_branches'    => $totalBranches,
                'total_companies'   => $totalCompanies,
                'open_positions'    => $openPositions,
            ],
            'user' => [
                'total_users'  => $totalUsers,
                'active_users' => $activeUsers,
            ],
            'leave' => [
                'pending_leave_requests'     => 0,
                'todays_leave'               => 0,
                'upcoming_leaves'            => 0,
                'leave_balance_summary'      => 0,
                'leave_approval_statistics'  => 0,
                'employees_on_leave'         => 0,
                'leave_trends_monthly'       => [],
            ],
        ];
    }
}
