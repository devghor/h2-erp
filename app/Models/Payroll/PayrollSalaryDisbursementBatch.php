<?php

namespace App\Models\Payroll;

use App\Enums\Payroll\SalaryDisbursementBatchStatusEnum;
use App\Enums\Payroll\SalaryDisbursementBatchTypeEnum;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PayrollSalaryDisbursementBatch extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'payroll_salary_disbursement_batches';

    protected $fillable = [
        'name',
        'year',
        'month',
        'type',
        'status',
        'total_basic',
        'total_gross',
        'total_deduction',
        'total_net',
        'employee_count',
        'remark',
        'submitted_by',
        'approved_by',
        'disbursed_by',
        'submitted_at',
        'approved_at',
        'disbursed_at',
        'disbursement_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'type'             => SalaryDisbursementBatchTypeEnum::class,
        'status'           => SalaryDisbursementBatchStatusEnum::class,
        'total_basic'      => 'decimal:2',
        'total_gross'      => 'decimal:2',
        'total_deduction'  => 'decimal:2',
        'total_net'        => 'decimal:2',
        'submitted_at'     => 'datetime',
        'approved_at'      => 'datetime',
        'disbursed_at'     => 'datetime',
        'disbursement_date' => 'date',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(PayrollSalaryDisbursementBatchEmployee::class, 'payroll_salary_disbursement_batch_id');
    }
}
