<?php

namespace App\Enums\Uam;

enum PermissionEnum: string
{

    // Dashboard
    case ReadGeneralDashboard = 'ReadGeneralDashboard';

    /**
     * Uam Module
     **/

        // User
    case CreateUamUser = 'CreateUamUser';
    case ReadUamUser = 'ReadUamUser';
    case UpdateUamUser = 'UpdateUamUser';
    case DeleteUamUser = 'DeleteUamUser';

        // Role
    case ReadUamRole = 'ReadUamRole';
    case CreateUamRole = 'CreateUamRole';
    case UpdateUamRole = 'UpdateUamRole';
    case DeleteUamRole = 'DeleteUamRole';

        // Permission
    case CreateUamPermission = 'CreateUamPermission';
    case ReadUamPermission = 'ReadUamPermission';
    case UpdateUamPermission = 'UpdateUamPermission';
    case DeleteUamPermission = 'DeleteUamPermission';
}
