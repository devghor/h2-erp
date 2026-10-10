<?php

namespace App\Models\Employee\EmployeeExperience;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class EmployeeExperience extends Model
{
    use BelongsToTenant, HasUlid;

    protected $table = 'employee_experience';

    protected $fillable = [
        'user_id',
        'company_name',
        'designation',
        'start_date',
        'end_date',
        'responsibilities',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
