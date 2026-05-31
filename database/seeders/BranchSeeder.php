<?php

namespace Database\Seeders;

use App\Models\Configuration\Branch\Branch;
use App\Models\Configuration\Company\Company;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $branches = [
            [
                'name'       => 'Head Office',
                'short_name' => 'HO',
                'code'       => 'HO-001',
                'address'    => '123 Corporate Avenue, Business District',
                'phone'      => '+880-2-9876543',
                'mobile'     => '+8801700000001',
                'email'      => 'headoffice@company.com',
            ],
            [
                'name'       => 'Dhaka Branch',
                'short_name' => 'DHK',
                'code'       => 'BR-001',
                'address'    => '45 Gulshan Avenue, Dhaka',
                'phone'      => '+880-2-8876543',
                'mobile'     => '+8801700000002',
                'email'      => 'dhaka@company.com',
            ],
            [
                'name'       => 'Chittagong Branch',
                'short_name' => 'CTG',
                'code'       => 'BR-002',
                'address'    => '78 Agrabad C/A, Chittagong',
                'phone'      => '+880-31-987654',
                'mobile'     => '+8801700000003',
                'email'      => 'chittagong@company.com',
            ],
            [
                'name'       => 'Sylhet Branch',
                'short_name' => 'SYL',
                'code'       => 'BR-003',
                'address'    => '22 Zindabazar Road, Sylhet',
                'phone'      => '+880-821-765432',
                'mobile'     => '+8801700000004',
                'email'      => 'sylhet@company.com',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['company_id' => $company->id, 'code' => $branch['code']],
                array_merge($branch, ['company_id' => $company->id])
            );
        }
    }
}
