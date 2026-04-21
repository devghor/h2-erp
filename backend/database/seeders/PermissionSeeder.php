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
            // Dashboard
            ['module' => 'General', 'group' => 'General > Dashboard', 'name' => PermissionEnum::ReadGeneralDashboard->value, 'label' => self::LABEL_READ],

            /**
             * Uam Module
             */

            // User
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::CreateUamUser->value, 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::ReadUamUser->value, 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::UpdateUamUser->value, 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::DeleteUamUser->value, 'label' => self::LABEL_DELETE],

            // Role
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::CreateUamRole->value, 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::ReadUamRole->value, 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::UpdateUamRole->value, 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::DeleteUamRole->value, 'label' => self::LABEL_DELETE],

            // Permission
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::CreateUamPermission->value, 'label' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::ReadUamPermission->value, 'label' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::UpdateUamPermission->value, 'label' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::DeleteUamPermission->value, 'label' => self::LABEL_DELETE],

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
