<?php

namespace App\Services\Configuration\DeskGroup;

use App\Models\Configuration\DeskGroup\DeskGroup;
use App\Services\Core\CoreService;

class DeskGroupService extends CoreService
{
    protected function model(): string
    {
        return DeskGroup::class;
    }
}
