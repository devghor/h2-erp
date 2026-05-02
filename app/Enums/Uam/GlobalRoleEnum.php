<?php

namespace App\Enums\Uam;

enum GlobalRoleEnum: string
{
    case SuperAdmin = 'super-admin';
    case Admin      = 'admin';
    case CompanyAdmin       = 'company-admin';
    case CompanyUser       = 'company-user';
}
