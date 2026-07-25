<?php

namespace App\Http\Controllers\Leave\LeaveType;

use App\DataTables\Leave\LeaveType\LeaveTypesDataTable;
use App\Enums\Employee\EmployeeTypeEnum;
use App\Enums\Leave\LeaveAvailabilityEnum;
use App\Enums\Leave\LeaveEligibilityEnum;
use App\Enums\Leave\LeaveFrequencyEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\LeaveType\StoreLeaveTypeRequest;
use App\Http\Requests\Leave\LeaveType\UpdateLeaveTypeRequest;
use App\Services\Leave\LeaveType\LeaveTypeService;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct(private LeaveTypeService $leaveTypeService) {}

    public function index(LeaveTypesDataTable $dataTable)
    {
        return $dataTable->renderInertia('leave/leave-types/index', [
            'employeeTypes'     => EmployeeTypeEnum::options(),
            'availabilityTypes' => LeaveAvailabilityEnum::options(),
            'frequencyTypes'    => LeaveFrequencyEnum::options(),
            'eligibilityTypes'  => LeaveEligibilityEnum::options(),
        ]);
    }

    public function create() {}

    public function store(StoreLeaveTypeRequest $request)
    {
        $this->leaveTypeService->create($request->validated());
        return redirect()->back()->with('success', 'Leave type created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateLeaveTypeRequest $request, string $id)
    {
        $this->leaveTypeService->update($request->validated(), $id);
        return redirect()->route('leave.leave-types.index')->with([
            'success' => __('Leave type updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->leaveTypeService->delete($id);
        return redirect()->route('leave.leave-types.index')->with([
            'success' => __('Leave type deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->leaveTypeService->bulkDelete($ids);

        return response()->json(['message' => 'Leave types deleted successfully.']);
    }
}