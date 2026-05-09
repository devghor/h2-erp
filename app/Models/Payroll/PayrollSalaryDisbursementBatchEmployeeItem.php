<?php

namespace App\Models\Payroll;

use App\Enums\Payroll\SalaryHeadCategoryEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PayrollSalaryDisbursementBatchEmployeeItem extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'payroll_salary_disbursement_batch_employee_items';

    protected $fillable = [
        'payroll_salary_disbursement_batch_employee_id',
        'payroll_salary_head_id',
        'head_name',
        'head_category',
        'amount',
        'is_adjustment',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'head_category' => SalaryHeadCategoryEnum::class,
        'amount'        => 'decimal:2',
        'is_adjustment' => 'boolean',
    ];

    public function batchEmployee(): BelongsTo
    {
        return $this->belongsTo(PayrollSalaryDisbursementBatchEmployee::class, 'payroll_salary_disbursement_batch_employee_id');
    }

    public function salaryHead(): BelongsTo
    {
        return $this->belongsTo(PayrollSalaryHead::class, 'payroll_salary_head_id');
    }
}
