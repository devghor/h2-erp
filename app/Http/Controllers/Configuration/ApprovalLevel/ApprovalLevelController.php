<?php

namespace App\Http\Controllers\Configuration\ApprovalLevel;

use App\DataTables\Configuration\ApprovalLevel\ApprovalLevelsDataTable;
use App\Enums\Configuration\ApprovalLevelTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\ApprovalLevel\StoreApprovalLevelRequest;
use App\Http\Requests\Configuration\ApprovalLevel\UpdateApprovalLevelRequest;
use App\Models\Configuration\Hrbp\Hrbp;
use App\Models\Configuration\Position\Position;
use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Services\Configuration\ApprovalLevel\ApprovalLevelService;
use Illuminate\Http\Request;

class ApprovalLevelController extends Controller
{
    public function __construct(private ApprovalLevelService $approvalLevelService) {}

    public function index(ApprovalLevelsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/approval-levels/index', [
            'typeOptions' => ApprovalLevelTypeEnum::options(),
            'positions' => Position::select(['id', 'name'])->get(),
            'positionGroups' => PositionGroup::select(['id', 'name'])->get(),
            'hrbps' => Hrbp::with('user:id,name,email')->select(['id', 'user_id'])->get(),
        ]);
    }

    public function create() {}

    public function store(StoreApprovalLevelRequest $request)
    {
        $this->approvalLevelService->create($request->validated());
        return redirect()->back()->with('success', 'Approval level created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateApprovalLevelRequest $request, string $id)
    {
        $this->approvalLevelService->update($request->validated(), $id);
        return redirect()->route('configuration.approval-levels.index')->with([
            'success' => __('Approval level updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->approvalLevelService->delete($id);
        return redirect()->route('configuration.approval-levels.index')->with([
            'success' => __('Approval level deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->approvalLevelService->bulkDelete($ids);

        return response()->json(['message' => 'Approval levels deleted successfully.']);
    }
}
