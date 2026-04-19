<?php

namespace Database\Seeders;

use App\Enums\Uam\PermissionEnum;
use App\Models\Configuration\Tenant;
use App\Models\Uam\Permission;
use App\Models\Uam\Role;
use App\Models\Uam\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    private array $admins = [
        [
            'user_name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'company_name' => 'Demo Company',
            'short_name' => 'DC',
            'permission' => PermissionEnum::SuperAdmin,
            'address' => '123 Main St, Anytown, USA',
            'phone' => '555-1234',
        ],
        [
            'user_name' => 'Company Admin',
            'email' => 'companyadmin@app.com',
            'company_name' => 'Dummy Company',
            'short_name' => 'dummy',
            'permission' => PermissionEnum::TenantAdmin,
            'address' => '456 Elm St, Othertown, USA',
            'phone' => '555-5678',
        ],
    ];

    public function run(): void
    {
        foreach ($this->admins as $admin) {;
            $tenant = Tenant::create([
                'name' => $admin['company_name'],
                'short_name' => $admin['short_name'],
                'email' => $admin['email'],
                'address' => $admin['address'],
                'phone' => $admin['phone'],
            ]);


            $user = User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['user_name'],
                    'password' => Hash::make('password'),
                    'tenant_id' => $tenant->id,
                ]
            );

            $role = null;

            if (PermissionEnum::SuperAdmin === $admin['permission']) {
                $role = Role::firstOrCreate([
                    'name' => 'Super Admin',
                ]);

                $permissions = Permission::whereIn('name', [PermissionEnum::SuperAdmin->value])->get();
                $role->syncPermissions($permissions);
            }

            if (PermissionEnum::TenantAdmin === $admin['permission']) {
                setPermissionsTeamId($tenant->id);

                $role = Role::firstOrCreate([
                    'name' => 'Company Admin',
                    'tenant_id' => $tenant->id,
                ]);

                $permissions = Permission::whereIn('name', [PermissionEnum::TenantAdmin->value])->get();
                $role->syncPermissions($permissions);
            }

            if ($role) {
                if (!$user->hasRole($role->name)) {
                    $user->assignRole($role);
                }
            }
        }
    }
}
