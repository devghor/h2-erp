<?php

namespace App\Services\Configuration\Position;

use App\Models\Configuration\Position\Position;
use App\Services\Core\CoreService;

class PositionService extends CoreService
{
    protected function model(): string
    {
        return Position::class;
    }
}
