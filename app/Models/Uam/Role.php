<?php

namespace App\Models\Uam;

use Spatie\Permission\Models\Role as SpatieRole;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'company_id',
        'description',
    ];
}
