<?php

namespace Database\Seeders;

use App\Models\Configuration\Company\Company;
use App\Models\Configuration\PositionGroup\PositionGroup;
use Illuminate\Database\Seeder;

class PositionGroupSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $groups = [
            ['name' => 'CEO',                 'code' => 'PG-CEO',  'description' => 'Chief Executive Officer level position group'],
            ['name' => 'Deputy CEO',          'code' => 'PG-DCEO', 'description' => 'Deputy Chief Executive Officer level position group'],
            ['name' => 'Head of Department',  'code' => 'PG-HOD',  'description' => 'Head of Department level position group'],
            ['name' => 'Division Head',       'code' => 'PG-DIV',  'description' => 'Division Head level position group'],
            ['name' => 'Below Division Head', 'code' => 'PG-BDIV', 'description' => 'Below Division Head level position group'],
            ['name' => 'Below HOD',           'code' => 'PG-BHOD', 'description' => 'Below Head of Department level position group'],
        ];

        foreach ($groups as $data) {
            PositionGroup::updateOrCreate(
                ['company_id' => $company->id, 'code' => $data['code']],
                array_merge($data, ['company_id' => $company->id])
            );
        }
    }
}
