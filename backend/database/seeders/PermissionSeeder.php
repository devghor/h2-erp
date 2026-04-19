<?php

namespace Database\Seeders;

use App\Enums\Uam\PermissionEnum;
use App\Models\Uam\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    const LABEL_CREATE = 'Create';
    const LABEL_READ = 'Read';
    const LABEL_UPDATE = 'Update';
    const LABEL_DELETE = 'Delete';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            /**
             * UnGrouped Permissions
             */
            ['module' => '', 'group' => '', 'name' => PermissionEnum::SuperAdmin->value, 'label' => PermissionEnum::SuperAdmin->name],
            ['module' => '', 'group' => '', 'name' => PermissionEnum::Admin->value, 'label' => PermissionEnum::Admin->name],
            ['module' => '', 'group' => '', 'name' => PermissionEnum::TenantAdmin->value, 'label' => PermissionEnum::TenantAdmin->name],

            /**
             * General Module
             */
            // Dashboard
            ['module' => 'General', 'group' => 'General > Dashboard', 'name' => 'ReadGeneralDashboard', 'label' => self::LABEL_READ],

            /**
             * Uam Module
             */

            // User
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'CreateUamUser', 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'ReadUamUser', 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'UpdateUamUser', 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'DeleteUamUser', 'label' => self::LABEL_DELETE],

            // Role
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'CreateUamRole', 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'ReadUamRole', 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'UpdateUamRole', 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'DeleteUamRole', 'label' => self::LABEL_DELETE],

            // Permission
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'CreateUamPermission', 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'ReadUamPermission', 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'UpdateUamPermission', 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'DeleteUamPermission', 'label' => self::LABEL_DELETE],

        ];


        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'module' => $permission['module'],
                    'group' => $permission['group'],
                    'label' => $permission['label'],
                ]
            );
        }

        // Delete permissions not in code
        Permission::whereNotIn('name', array_column($permissions, 'name'))->delete();
    }
}
