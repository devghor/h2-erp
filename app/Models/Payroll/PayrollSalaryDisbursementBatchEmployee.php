<?php

namespace App\Models\Payroll;

use App\Enums\Payroll\PaymentModeEnum;
use App\Enums\Payroll\PaymentStatusEnum;
use App\Models\Employee\Employee\Employee;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PayrollSalaryDisbursementBatchEmployee extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'payroll_salary_disbursement_batch_employees';

    protected $fillable = [
        'payroll_salary_disbursement_batch_id',
        'user_id',
        'payroll_employee_salary_profile_id',
        'basic_amount',
        'gross_amount',
        'deduction_amount',
        'net_amount',
        'payment_mode',
        'payment_status',
        'payment_reference',
        'paid_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_mode'    => PaymentModeEnum::class,
        'payment_status'  => PaymentStatusEnum::class,
        'basic_amount'    => 'decimal:2',
        'gross_amount'    => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'net_amount'      => 'decimal:2',
        'paid_at'         => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(PayrollSalaryDisbursementBatch::class, 'payroll_salary_disbursement_batch_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollSalaryDisbursementBatchEmployeeItem::class, 'payroll_salary_disbursement_batch_employee_id');
    }
}
