<?php

namespace App\Http\Controllers\Configuration\PositionGroup;

use App\DataTables\Configuration\PositionGroup\PositionGroupsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\PositionGroup\StorePositionGroupRequest;
use App\Http\Requests\Configuration\PositionGroup\UpdatePositionGroupRequest;
use App\Services\Configuration\PositionGroup\PositionGroupService;
use Illuminate\Http\Request;

class PositionGroupController extends Controller
{
    public function __construct(private PositionGroupService $positionGroupService) {}

    public function index(PositionGroupsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/position-groups/index');
    }

    public function create() {}

    public function store(StorePositionGroupRequest $request)
    {
        $this->positionGroupService->create($request->validated());

        return redirect()->back()->with('success', 'Position group created successfully.');
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdatePositionGroupRequest $request, string $id)
    {
        $this->positionGroupService->update($request->validated(), $id);

        return redirect()->route('configuration.position-groups.index')->with([
            'success' => __('Position group updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->positionGroupService->delete($id);

        return redirect()->route('configuration.position-groups.index')->with([
            'success' => __('Position group deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->positionGroupService->bulkDelete($ids);

        return response()->json(['message' => 'Position groups deleted successfully.']);
    }
}
