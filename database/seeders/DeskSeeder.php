<?php

namespace Database\Seeders;

use App\Enums\Configuration\Desk\DeskGroupEnum;
use App\Models\Configuration\Company\Company;
use App\Models\Configuration\Desk\Desk;
use App\Models\Configuration\DeskGroup\DeskGroup;
use Illuminate\Database\Seeder;

class DeskSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $deskGroups = DeskGroup::where('company_id', $company->id)
            ->get()
            ->keyBy(fn($g) => $g->type->value);

        $desks = [
            ['name' => 'CEO Desk',           'description' => 'Office of the Chief Executive Officer',  'type' => DeskGroupEnum::Ceo],
            ['name' => 'Deputy CEO Desk',    'description' => 'Office of the Deputy CEO',               'type' => DeskGroupEnum::DyCeo],
            ['name' => 'Technology Desk',    'description' => 'Head of Technology department',          'type' => DeskGroupEnum::Hod],
            ['name' => 'Operations Desk',    'description' => 'Head of Operations department',          'type' => DeskGroupEnum::Hod],
            ['name' => 'Finance Desk',       'description' => 'Head of Finance department',             'type' => DeskGroupEnum::Hod],
            ['name' => 'HR Desk',            'description' => 'Head of Human Resources department',     'type' => DeskGroupEnum::DivisionHead],
            ['name' => 'Software Division',  'description' => 'Software engineering division',          'type' => DeskGroupEnum::DivisionHead],
        ];

        foreach ($desks as $data) {
            $groupId = $deskGroups[$data['type']->value]?->id;
            Desk::updateOrCreate(
                ['company_id' => $company->id, 'name' => $data['name']],
                ['company_id' => $company->id, 'name' => $data['name'], 'description' => $data['description'], 'desk_group_id' => $groupId]
            );
        }

        // Desk with parent reference
        $softwareDivision = Desk::where('company_id', $company->id)->where('name', 'Software Division')->first();
        $groupId = $deskGroups[DeskGroupEnum::BelowDivisionHead->value]?->id;

        Desk::updateOrCreate(
            ['company_id' => $company->id, 'name' => 'Development Desk'],
            ['company_id' => $company->id, 'name' => 'Development Desk', 'description' => 'Software development team desk', 'parent_id' => $softwareDivision?->id, 'desk_group_id' => $groupId]
        );
    }
}
