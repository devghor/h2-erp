<?php

namespace Database\Seeders;

use App\Enums\Uam\GlobalRoleEnum;
use App\Enums\Uam\PermissionEnum;
use App\Models\Configuration\Company;
use App\Models\Uam\Role;
use App\Models\Uam\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    private array $data = [
        [
            'user_name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'company_name' => 'Super Company',
            'short_name' => 'DC',
            'address' => '123 Main St, Anytown, USA',
            'phone' => '555-1234',
            'global_role' => GlobalRoleEnum::SuperAdmin->value,
            'role' => null
        ],
        [
            'user_name' => 'Dummy Admin',
            'email' => 'dummyadmin@app.com',
            'company_name' => 'Dummy Company',
            'short_name' => 'dummy',
            'address' => '456 Elm St, Othertown, USA',
            'phone' => '555-5678',
            'global_role' => null,
            'role' => 'Company Admin'
        ],
    ];

    public function run(): void
    {
        foreach ($this->data as $d) {;
            if ($d['global_role'] === GlobalRoleEnum::SuperAdmin->value) {
                $company = Company::create([
                    'name' => $d['company_name'],
                    'short_name' => $d['short_name'],
                    'email' => $d['email'],
                    'address' => $d['address'],
                    'phone' => $d['phone'],
                ]);

                $user = User::updateOrCreate(
                    ['email' => $d['email']],
                    [
                        'name' => $d['user_name'],
                        'password' => Hash::make('password'),
                        'company_id' => $company->id,
                        'global_role' => $d['global_role'],
                    ]
                );
            }

            if (!$d['global_role']) {
                $company = Company::create([
                    'name' => $d['company_name'],
                    'short_name' => $d['short_name'],
                    'email' => $d['email'],
                    'address' => $d['address'],
                    'phone' => $d['phone'],
                ]);

                $user = User::updateOrCreate(
                    ['email' => $d['email']],
                    [
                        'name' => $d['user_name'],
                        'password' => Hash::make('password'),
                        'company_id' => $company->id,
                        'global_role' => $d['global_role'],
                    ]
                );

                setPermissionsTeamId($company->id);

                $role = Role::updateOrCreate(
                    ['name' => $d['role']],
                    [
                        'name' => $d['role'],
                        'company_id' => $company->id,
                    ]
                );

                $role->syncPermissions(PermissionEnum::cases());

                $user->assignRole($role->id);
            }
        }
    }
}
