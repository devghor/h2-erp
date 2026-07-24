<?php

namespace App\Http\Controllers\Configuration\ApprovalFlow;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\ApprovalFlow\StoreApprovalFlowGroupRequest;
use App\Http\Requests\Configuration\ApprovalFlow\UpdateApprovalFlowGroupRequest;
use App\Services\Configuration\ApprovalFlow\ApprovalFlowGroupService;

class ApprovalFlowGroupController extends Controller
{
    public function __construct(private ApprovalFlowGroupService $approvalFlowGroupService) {}

    public function store(StoreApprovalFlowGroupRequest $request)
    {
        $this->approvalFlowGroupService->create($request->validated());

        return redirect()->back()->with('success', 'Approval flow group added successfully.');
    }

    public function update(UpdateApprovalFlowGroupRequest $request, string $id)
    {
        $this->approvalFlowGroupService->update($request->validated(), $id);

        return redirect()->back()->with('success', 'Approval flow group updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->approvalFlowGroupService->delete($id);

        return redirect()->back()->with('success', 'Approval flow group deleted successfully.');
    }
}
