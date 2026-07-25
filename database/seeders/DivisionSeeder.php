<?php

namespace Database\Seeders;

use App\Models\Configuration\Company\Company;
use App\Models\Configuration\Division\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $divisions = [
            [
                'name'        => 'Human Resources',
                'code'        => 'HR',
                'description' => 'Manages recruitment, employee relations, and HR operations',
            ],
            [
                'name'        => 'Finance & Accounts',
                'code'        => 'FIN',
                'description' => 'Handles financial planning, accounting, and reporting',
            ],
            [
                'name'        => 'Information Technology',
                'code'        => 'IT',
                'description' => 'Manages IT infrastructure, software, and digital systems',
            ],
            [
                'name'        => 'Operations',
                'code'        => 'OPS',
                'description' => 'Oversees daily business operations and logistics',
            ],
            [
                'name'        => 'Marketing & Sales',
                'code'        => 'MKT',
                'description' => 'Drives brand awareness, marketing campaigns, and sales',
            ],
        ];

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['company_id' => $company->id, 'name' => $division['name']],
                array_merge($division, ['company_id' => $company->id])
            );
        }
    }
}
