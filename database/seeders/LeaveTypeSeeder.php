<?php

namespace Database\Seeders;

use App\Enums\Leave\LeaveAvailabilityEnum;
use App\Enums\Leave\LeaveEligibilityEnum;
use App\Enums\Leave\LeaveFrequencyEnum;
use App\Models\Configuration\Company\Company;
use App\Models\Leave\LeaveType\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $leaveTypes = [
            // Paid leaves
            [
                'name'               => 'Casual Leave',
                'code'               => 'CL',
                'leave_count'        => 12,
                'max_balance'        => 12,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::CalculateProportionally,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Sick Leave',
                'code'               => 'SL',
                'leave_count'        => 14,
                'max_balance'        => 14,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::CalculateProportionally,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => true,
                'carry_forward'      => false,
                'allow_pending_leave'=> true,
            ],
            [
                'name'               => 'Earned Leave',
                'code'               => 'EL',
                'leave_count'        => 15,
                'max_balance'        => 30,
                'availability'       => LeaveAvailabilityEnum::AfterHrApproval,
                'frequency'          => LeaveFrequencyEnum::OnceAYear,
                'eligibility'        => LeaveEligibilityEnum::AfterOneYearConfirmedService,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => true,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Annual Leave',
                'code'               => 'AL',
                'leave_count'        => 20,
                'max_balance'        => 20,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::OnceAYear,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent', 'contract'],
                'advanced_leave'     => true,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],

            // Medical / special
            [
                'name'               => 'Medical Leave',
                'code'               => 'ML',
                'leave_count'        => 7,
                'max_balance'        => 7,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::CalculateProportionally,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => true,
                'carry_forward'      => false,
                'allow_pending_leave'=> true,
            ],
            [
                'name'               => 'Maternity Leave',
                'code'               => 'MNL',
                'leave_count'        => 182,
                'max_balance'        => 182,
                'availability'       => LeaveAvailabilityEnum::AfterHrApproval,
                'frequency'          => LeaveFrequencyEnum::OnceAServicePeriod,
                'eligibility'        => LeaveEligibilityEnum::AfterOneYearConfirmedService,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => true,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Paternity Leave',
                'code'               => 'PTL',
                'leave_count'        => 5,
                'max_balance'        => 5,
                'availability'       => LeaveAvailabilityEnum::AfterHrApproval,
                'frequency'          => LeaveFrequencyEnum::OnceAYear,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => true,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Menstrual Leave',
                'code'               => 'MLL',
                'leave_count'        => 12,
                'max_balance'        => 12,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::CalculateProportionally,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],

            // Unpaid / others
            [
                'name'               => 'Leave Without Pay',
                'code'               => 'LWP',
                'leave_count'        => 0,
                'max_balance'        => 0,
                'availability'       => LeaveAvailabilityEnum::AfterHrApproval,
                'frequency'          => LeaveFrequencyEnum::OnceAYear,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent', 'contract'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Compensatory Off',
                'code'               => 'CO',
                'leave_count'        => 0,
                'max_balance'        => 30,
                'availability'       => LeaveAvailabilityEnum::AfterHrApproval,
                'frequency'          => LeaveFrequencyEnum::CalculateProportionally,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
            [
                'name'               => 'Bereavement Leave',
                'code'               => 'BL',
                'leave_count'        => 5,
                'max_balance'        => 5,
                'availability'       => LeaveAvailabilityEnum::Always,
                'frequency'          => LeaveFrequencyEnum::OnceAYear,
                'eligibility'        => LeaveEligibilityEnum::FromJoining,
                'employee_type'      => ['permanent'],
                'advanced_leave'     => false,
                'document_required'  => false,
                'carry_forward'      => false,
                'allow_pending_leave'=> false,
            ],
        ];

        foreach ($leaveTypes as $data) {
            LeaveType::updateOrCreate(
                ['company_id' => $company->id, 'code' => $data['code']],
                array_merge($data, ['company_id' => $company->id])
            );
        }
    }
}
