<?php

namespace Database\Seeders;

use App\Enums\Configuration\Desk\DeskGroupEnum;
use App\Models\Configuration\Company\Company;
use App\Models\Configuration\DeskGroup\DeskGroup;
use Illuminate\Database\Seeder;

class DeskGroupSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $groups = [
            ['name' => 'CEO',                 'code' => 'DG-CEO',  'type' => DeskGroupEnum::Ceo,              'description' => 'Chief Executive Officer level desk group'],
            ['name' => 'Deputy CEO',          'code' => 'DG-DCEO', 'type' => DeskGroupEnum::DyCeo,            'description' => 'Deputy Chief Executive Officer level desk group'],
            ['name' => 'Head of Department',  'code' => 'DG-HOD',  'type' => DeskGroupEnum::Hod,              'description' => 'Head of Department level desk group'],
            ['name' => 'Division Head',       'code' => 'DG-DIV',  'type' => DeskGroupEnum::DivisionHead,     'description' => 'Division Head level desk group'],
            ['name' => 'Below Division Head', 'code' => 'DG-BDIV', 'type' => DeskGroupEnum::BelowDivisionHead,'description' => 'Below Division Head level desk group'],
            ['name' => 'Below HOD',           'code' => 'DG-BHOD', 'type' => DeskGroupEnum::BelowHod,         'description' => 'Below Head of Department level desk group'],
        ];

        foreach ($groups as $data) {
            DeskGroup::updateOrCreate(
                ['company_id' => $company->id, 'code' => $data['code']],
                array_merge($data, ['company_id' => $company->id])
            );
        }
    }
}
