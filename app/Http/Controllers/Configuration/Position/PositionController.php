<?php

namespace App\Http\Controllers\Configuration\Position;

use App\DataTables\Configuration\Position\PositionsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Position\StorePositionRequest;
use App\Http\Requests\Configuration\Position\UpdatePositionRequest;
use App\Models\Configuration\Branch\Branch;
use App\Models\Configuration\Department\Department;
use App\Models\Configuration\Division\Division;
use App\Models\Configuration\Position\Position;
use App\Models\Configuration\PositionGroup\PositionGroup;
use App\Services\Configuration\Position\PositionService;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct(private PositionService $positionService) {}

    public function index(PositionsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/positions/index', [
            'branches' => Branch::select(['id', 'name'])->get(),
            'divisions' => Division::select(['id', 'name'])->get(),
            'departments' => Department::select(['id', 'name'])->get(),
            'positionGroups' => PositionGroup::select(['id', 'name'])->get(),
            'positions' => Position::select(['id', 'name', 'parent_id'])->get(),
        ]);
    }

    public function create() {}

    public function store(StorePositionRequest $request)
    {
        $this->positionService->create($request->validated());
        return redirect()->back()->with('success', 'Position created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdatePositionRequest $request, string $id)
    {
        $this->positionService->update($request->validated(), $id);
        return redirect()->route('configuration.positions.index')->with([
            'success' => __('Position updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->positionService->delete($id);
        return redirect()->route('configuration.positions.index')->with([
            'success' => __('Position deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->positionService->bulkDelete($ids);

        return response()->json(['message' => 'Positions deleted successfully.']);
    }
}
