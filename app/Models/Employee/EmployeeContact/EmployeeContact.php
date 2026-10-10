<?php

namespace App\Models\Employee\EmployeeContact;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class EmployeeContact extends Model
{
    use BelongsToTenant, HasUlid;

    protected $table = 'employee_contacts';

    protected $fillable = [
        'user_id',
        'contact_name',
        'relationship',
        'phone',
    ];
}
