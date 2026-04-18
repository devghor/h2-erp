<?php

namespace App\Models\Uam;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'label',
        'module',
        'group',
        'guard_name',
        'company_id',
    ];
}
