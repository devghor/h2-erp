<?php

namespace Database\Seeders;

use App\Models\Uam\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    const KEY_CREATE = 'Create';
    const KEY_READ = 'Read';
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
            ['module' => 'General', 'group' => 'General > Dashboard', 'name' => 'READ_GENERAL_DASHBOARD', 'display_name' => self::KEY_READ],

            /**
             * Uam Module
             */

            // User
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'CREATE_UAM_USER', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'READ_UAM_USER', 'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'UPDATE_UAM_USER', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => 'DELETE_UAM_USER', 'display_name' => self::KEY_DELETE],

            // Role
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'CREATE_UAM_ROLE', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'READ_UAM_ROLE', 'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'UPDATE_UAM_ROLE', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => 'DELETE_UAM_ROLE', 'display_name' => self::KEY_DELETE],

            // Permission
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'CREATE_UAM_PERMISSION', 'display_name' => self::KEY_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'READ_UAM_PERMISSION', 'display_name' => self::KEY_READ],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'UPDATE_UAM_PERMISSION', 'display_name' => self::KEY_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => 'DELETE_UAM_PERMISSION', 'display_name' => self::KEY_DELETE],
        ];


        foreach ($permissions as $i => $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'guard_name' => 'web',
                    'module' => $permission['module'],
                    'group' => $permission['group'],
                    'display_name' => $permission['display_name'],
                    'order' => $i + 1,
                ]
            );
        }

        // Delete permissions not in code
        Permission::whereNotIn('name', array_column($permissions, 'name'))->delete();
    }
}
