<?php

namespace App\Services\Product\Unit;

use App\Models\Product\Unit;
use App\Services\Core\CoreService;

class UnitService extends CoreService
{
    protected function model(): string
    {
        return Unit::class;
    }
}
