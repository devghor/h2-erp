<?php

namespace Database\Seeders;

use App\Models\Configuration\Company\Company;
use App\Models\Configuration\Position\Position;
use App\Models\Configuration\PositionGroup\PositionGroup;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $positionGroups = PositionGroup::where('company_id', $company->id)
            ->get()
            ->keyBy('code');

        $positions = [
            ['name' => 'CEO Position',          'description' => 'Office of the Chief Executive Officer',  'group_code' => 'PG-CEO'],
            ['name' => 'Deputy CEO Position',   'description' => 'Office of the Deputy CEO',               'group_code' => 'PG-DCEO'],
            ['name' => 'Technology Position',   'description' => 'Head of Technology department',          'group_code' => 'PG-HOD'],
            ['name' => 'Operations Position',   'description' => 'Head of Operations department',          'group_code' => 'PG-HOD'],
            ['name' => 'Finance Position',      'description' => 'Head of Finance department',             'group_code' => 'PG-HOD'],
            ['name' => 'HR Position',           'description' => 'Head of Human Resources department',     'group_code' => 'PG-DIV'],
            ['name' => 'Software Division',     'description' => 'Software engineering division',          'group_code' => 'PG-DIV'],
        ];

        foreach ($positions as $data) {
            $groupId = $positionGroups[$data['group_code']]?->id;
            Position::updateOrCreate(
                ['company_id' => $company->id, 'name' => $data['name']],
                ['company_id' => $company->id, 'name' => $data['name'], 'description' => $data['description'], 'position_group_id' => $groupId]
            );
        }

        // Position with parent reference
        $softwareDivision = Position::where('company_id', $company->id)->where('name', 'Software Division')->first();
        $groupId = $positionGroups['PG-BDIV']?->id;

        Position::updateOrCreate(
            ['company_id' => $company->id, 'name' => 'Development Position'],
            ['company_id' => $company->id, 'name' => 'Development Position', 'description' => 'Software development team position', 'parent_id' => $softwareDivision?->id, 'position_group_id' => $groupId]
        );
    }
}
