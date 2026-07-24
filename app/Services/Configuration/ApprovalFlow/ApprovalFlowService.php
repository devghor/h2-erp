<?php

namespace App\Services\Configuration\ApprovalFlow;

use App\Models\Configuration\ApprovalFlow\ApprovalFlow;
use App\Services\Core\CoreService;

class ApprovalFlowService extends CoreService
{
    protected function model(): string
    {
        return ApprovalFlow::class;
    }
}
