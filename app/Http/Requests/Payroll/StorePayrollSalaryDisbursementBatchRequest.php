<?php

namespace App\Http\Requests\Payroll;

use App\Enums\Payroll\SalaryDisbursementBatchTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePayrollSalaryDisbursementBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'year'       => ['required', 'integer', 'min:2000', 'max:2100'],
            'month'      => ['required', 'integer', 'min:1', 'max:12'],
            'type'       => ['required', new Enum(SalaryDisbursementBatchTypeEnum::class)],
            'remark'     => ['nullable', 'string'],
            'user_ids'   => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
