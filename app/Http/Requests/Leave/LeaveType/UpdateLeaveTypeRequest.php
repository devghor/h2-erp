<?php

namespace App\Http\Requests\Leave\LeaveType;

use App\Enums\Employee\EmployeeTypeEnum;
use App\Enums\Leave\LeaveAvailabilityEnum;
use App\Enums\Leave\LeaveEligibilityEnum;
use App\Enums\Leave\LeaveFrequencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:255',
            'code'                => 'nullable|string|max:100',
            'leave_count'         => 'required|integer|min:0',
            'max_balance'         => 'required|integer|min:0',
            'availability'        => ['required', Rule::enum(LeaveAvailabilityEnum::class)],
            'frequency'           => ['required', Rule::enum(LeaveFrequencyEnum::class)],
            'eligibility'         => ['required', Rule::enum(LeaveEligibilityEnum::class)],
            'employee_type'       => 'required|array|min:1',
            'employee_type.*'     => ['required', Rule::enum(EmployeeTypeEnum::class)],
            'advanced_leave'      => 'boolean',
            'document_required'   => 'boolean',
            'carry_forward'       => 'boolean',
            'allow_pending_leave' => 'boolean',
        ];
    }
}