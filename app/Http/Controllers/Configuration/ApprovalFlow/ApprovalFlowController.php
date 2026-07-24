<?php

namespace App\Http\Controllers\Configuration\ApprovalFlow;

use App\DataTables\Configuration\ApprovalFlow\ApprovalFlowsDataTable;
use App\Enums\Configuration\ApprovalFlowTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\ApprovalFlow\StoreApprovalFlowRequest;
use App\Http\Requests\Configuration\ApprovalFlow\UpdateApprovalFlowRequest;
use App\Models\Configuration\ApprovalFlow\ApprovalFlow;
use App\Models\Configuration\ApprovalLevel\ApprovalLevel;
use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Services\Configuration\ApprovalFlow\ApprovalFlowService;
use Illuminate\Http\Request;

class ApprovalFlowController extends Controller
{
    public function __construct(private ApprovalFlowService $approvalFlowService) {}

    public function index(ApprovalFlowsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/approval-flows/index', [
            'typeOptions' => ApprovalFlowTypeEnum::options(),
        ]);
    }

    public function create() {}

    public function store(StoreApprovalFlowRequest $request)
    {
        $this->approvalFlowService->create($request->validated());
        return redirect()->back()->with('success', 'Approval flow created successfully.');
    }

    public function show(string $id)
    {
        $flow = ApprovalFlow::with(['groups.positionGroup', 'groups.items.approvalLevel.hrbp'])->findOrFail($id);

        return inertia('configuration/approval-flows/show', [
            'flow' => $flow,
            'positionGroups' => PositionGroup::select(['id', 'name'])->get(),
            'approvalLevels' => ApprovalLevel::select(['id', 'name', 'type'])->get(),
            'typeOptions' => ApprovalFlowTypeEnum::options(),
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateApprovalFlowRequest $request, string $id)
    {
        $this->approvalFlowService->update($request->validated(), $id);
        return redirect()->route('configuration.approval-flows.index')->with([
            'success' => __('Approval flow updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->approvalFlowService->delete($id);
        return redirect()->route('configuration.approval-flows.index')->with([
            'success' => __('Approval flow deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->approvalFlowService->bulkDelete($ids);

        return response()->json(['message' => 'Approval flows deleted successfully.']);
    }
}
