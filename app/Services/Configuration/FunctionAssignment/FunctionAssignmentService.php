<?php

namespace App\Services\Configuration\FunctionAssignment;

use App\Models\Configuration\FunctionAssignment\FunctionAssignment;
use App\Services\Core\CoreService;

class FunctionAssignmentService extends CoreService
{
    protected function model(): string
    {
        return FunctionAssignment::class;
    }
}
