<?php

namespace App\Services\Configuration\Shift;

use App\Models\Configuration\Shift\Shift;
use App\Services\Core\CoreService;

class ShiftService extends CoreService
{
    protected function model(): string
    {
        return Shift::class;
    }
}
