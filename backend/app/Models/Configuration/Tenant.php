<?php

namespace App\Models\Configuration;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $table = 'tenants';

    public static function getCustomColumns(): array
    {
        return array_merge(parent::getCustomColumns(), [
            'name',
            'short_name',
            'email',
            'address',
            'phone',
            'logo',
        ]);
    }
}
