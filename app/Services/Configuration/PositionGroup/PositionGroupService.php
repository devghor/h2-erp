<?php

namespace App\Services\Configuration\PositionGroup;

use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Services\Core\CoreService;

class PositionGroupService extends CoreService
{
    protected function model(): string
    {
        return PositionGroup::class;
    }
}
