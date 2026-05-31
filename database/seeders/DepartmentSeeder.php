<?php

namespace Database\Seeders;

use App\Models\Configuration\Company\Company;
use App\Models\Configuration\Department\Department;
use App\Models\Configuration\Division\Division;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $departments = [
            // Human Resources
            ['name' => 'Recruitment',          'division' => 'Human Resources',        'description' => 'Talent acquisition and onboarding'],
            ['name' => 'Payroll & Benefits',    'division' => 'Human Resources',        'description' => 'Salary processing and employee benefits'],
            ['name' => 'Employee Relations',    'division' => 'Human Resources',        'description' => 'Workplace relations and compliance'],

            // Finance & Accounts
            ['name' => 'Accounts Payable',      'division' => 'Finance & Accounts',     'description' => 'Vendor payments and expense management'],
            ['name' => 'Accounts Receivable',   'division' => 'Finance & Accounts',     'description' => 'Customer invoicing and collections'],
            ['name' => 'Financial Reporting',   'division' => 'Finance & Accounts',     'description' => 'Financial statements and audit support'],

            // Information Technology
            ['name' => 'Software Development',  'division' => 'Information Technology', 'description' => 'Application development and maintenance'],
            ['name' => 'IT Infrastructure',     'division' => 'Information Technology', 'description' => 'Servers, networks, and hardware management'],

            // Operations
            ['name' => 'Logistics',             'division' => 'Operations',             'description' => 'Supply chain and delivery coordination'],
            ['name' => 'Quality Assurance',     'division' => 'Operations',             'description' => 'Product and process quality control'],

            // Marketing & Sales
            ['name' => 'Digital Marketing',     'division' => 'Marketing & Sales',      'description' => 'Online marketing and social media'],
            ['name' => 'Sales',                 'division' => 'Marketing & Sales',      'description' => 'Revenue generation and client management'],
        ];

        $divisionCache = [];

        foreach ($departments as $dept) {
            $divisionName = $dept['division'];

            if (!isset($divisionCache[$divisionName])) {
                $divisionCache[$divisionName] = Division::where('company_id', $company->id)
                    ->where('name', $divisionName)
                    ->first();
            }

            $division = $divisionCache[$divisionName];

            Department::updateOrCreate(
                ['company_id' => $company->id, 'name' => $dept['name']],
                [
                    'company_id'  => $company->id,
                    'name'        => $dept['name'],
                    'division_id' => $division?->id,
                    'description' => $dept['description'],
                ]
            );
        }
    }
}
