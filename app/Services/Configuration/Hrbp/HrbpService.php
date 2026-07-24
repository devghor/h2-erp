<?php

namespace App\Services\Configuration\Hrbp;

use App\Models\Configuration\Hrbp\Hrbp;
use App\Services\Core\CoreService;

class HrbpService extends CoreService
{
    protected function model(): string
    {
        return Hrbp::class;
    }
}
