<?php

namespace Database\Seeders;

use App\Models\Uam\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    const KEY_CREATE = 'Create';
    const KEY_READ   = 'Read';
    const KEY_UPDATE = 'Update';
    const KEY_DELETE = 'Delete';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            /**
             * General Module
             */

            // Dashboard
            ['module' => 'General', 'group' => 'Dashboard', 'name' => 'READ_GENERAL_DASHBOARD', 'display_name' => self::KEY_READ],

            /**
             * UAM Module
             */

            // User
            ['module' => 'Uam', 'group' => 'Users', 'name' => 'CREATE_UAM_USER', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Users', 'name' => 'READ_UAM_USER',   'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Users', 'name' => 'UPDATE_UAM_USER', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Users', 'name' => 'DELETE_UAM_USER', 'display_name' => self::KEY_DELETE],

            // Role
            ['module' => 'Uam', 'group' => 'Roles', 'name' => 'CREATE_UAM_ROLE', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Roles', 'name' => 'READ_UAM_ROLE',   'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Roles', 'name' => 'UPDATE_UAM_ROLE', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Roles', 'name' => 'DELETE_UAM_ROLE', 'display_name' => self::KEY_DELETE],

            // Permission
            ['module' => 'Uam', 'group' => 'Permissions', 'name' => 'CREATE_UAM_PERMISSION', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Permissions', 'name' => 'READ_UAM_PERMISSION',   'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Permissions', 'name' => 'UPDATE_UAM_PERMISSION', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Permissions', 'name' => 'DELETE_UAM_PERMISSION', 'display_name' => self::KEY_DELETE],
        ];

        foreach ($permissions as $i => $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'guard_name'   => 'api',
                    'module'       => $permission['module'],
                    'group'        => $permission['group'],
                    'display_name' => $permission['display_name'],
                    'order' => $i + 1,

                ]
            );
        }

        // Remove any permissions no longer defined in code
        Permission::whereNotIn('name', array_column($permissions, 'name'))->delete();
    }
}
