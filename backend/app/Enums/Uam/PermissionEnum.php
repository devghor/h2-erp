<?php

namespace App\Enums\Uam;

enum PermissionEnum: string
{
    case SuperAdmin = 'super_admin';
    case TenantAdmin = 'tenant_admin';
}
