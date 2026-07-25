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
            ['name' => 'Recruitment',          'code' => 'REC', 'division' => 'Human Resources',        'description' => 'Talent acquisition and onboarding'],
            ['name' => 'Payroll & Benefits',    'code' => 'PYB', 'division' => 'Human Resources',        'description' => 'Salary processing and employee benefits'],
            ['name' => 'Employee Relations',    'code' => 'EMR', 'division' => 'Human Resources',        'description' => 'Workplace relations and compliance'],

            // Finance & Accounts
            ['name' => 'Accounts Payable',      'code' => 'AP',  'division' => 'Finance & Accounts',     'description' => 'Vendor payments and expense management'],
            ['name' => 'Accounts Receivable',   'code' => 'AR',  'division' => 'Finance & Accounts',     'description' => 'Customer invoicing and collections'],
            ['name' => 'Financial Reporting',   'code' => 'FR',  'division' => 'Finance & Accounts',     'description' => 'Financial statements and audit support'],

            // Information Technology
            ['name' => 'Software Development',  'code' => 'SD',  'division' => 'Information Technology', 'description' => 'Application development and maintenance'],
            ['name' => 'IT Infrastructure',     'code' => 'ITINF', 'division' => 'Information Technology', 'description' => 'Servers, networks, and hardware management'],

            // Operations
            ['name' => 'Logistics',             'code' => 'LOG', 'division' => 'Operations',             'description' => 'Supply chain and delivery coordination'],
            ['name' => 'Quality Assurance',     'code' => 'QA',  'division' => 'Operations',             'description' => 'Product and process quality control'],

            // Marketing & Sales
            ['name' => 'Digital Marketing',     'code' => 'DM',  'division' => 'Marketing & Sales',      'description' => 'Online marketing and social media'],
            ['name' => 'Sales',                 'code' => 'SAL', 'division' => 'Marketing & Sales',      'description' => 'Revenue generation and client management'],
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
                    'code'        => $dept['code'],
                    'division_id' => $division?->id,
                    'description' => $dept['description'],
                ]
            );
        }
    }
}
