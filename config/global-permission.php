<?php

use App\Enums\Uam\GlobalRoleEnum;
use App\Enums\Uam\PermissionEnum;

return [
    GlobalRoleEnum::SuperAdmin->value => [
        ...array_map(fn($p) => $p->value, PermissionEnum::cases()),
    ],

    GlobalRoleEnum::Admin->value => [
        PermissionEnum::ReadGeneralDashboard->value,

        /**
         * UAM module
         */
        PermissionEnum::CreateUamUser->value,
        PermissionEnum::ReadUamUser->value,
        PermissionEnum::UpdateUamUser->value,
    ],
];
