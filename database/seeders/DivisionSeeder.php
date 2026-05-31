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
                'description' => 'Manages recruitment, employee relations, and HR operations',
            ],
            [
                'name'        => 'Finance & Accounts',
                'description' => 'Handles financial planning, accounting, and reporting',
            ],
            [
                'name'        => 'Information Technology',
                'description' => 'Manages IT infrastructure, software, and digital systems',
            ],
            [
                'name'        => 'Operations',
                'description' => 'Oversees daily business operations and logistics',
            ],
            [
                'name'        => 'Marketing & Sales',
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
