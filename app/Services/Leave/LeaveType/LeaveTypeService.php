<?php

namespace App\Services\Leave\LeaveType;

use App\Models\Leave\LeaveType\LeaveType;
use App\Services\Core\CoreService;

class LeaveTypeService extends CoreService
{
    protected function model(): string
    {
        return LeaveType::class;
    }

    public function getLeaveTypeOptions()
    {
        return $this->model->select('id', 'name', 'code')->get();
    }
}