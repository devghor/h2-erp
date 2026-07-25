<?php

namespace App\Models\Leave\LeaveType;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class LeaveType extends Model
{
    use HasUlid, BelongsToTenant;

    protected $table = 'leave_types';

    protected $fillable = [
        'name',
        'code',
        'leave_count',
        'max_balance',
        'availability',
        'frequency',
        'eligibility',
        'employee_type',
        'advanced_leave',
        'document_required',
        'carry_forward',
        'allow_pending_leave',
    ];

    protected $casts = [
        'employee_type'       => 'array',
        'advanced_leave'      => 'boolean',
        'document_required'   => 'boolean',
        'carry_forward'       => 'boolean',
        'allow_pending_leave' => 'boolean',
    ];
}