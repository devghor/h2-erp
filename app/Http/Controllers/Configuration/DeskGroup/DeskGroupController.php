<?php

namespace App\Http\Controllers\Configuration\DeskGroup;

use App\DataTables\Configuration\DeskGroup\DeskGroupsDataTable;
use App\Enums\Configuration\Desk\DeskGroupEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\DeskGroup\StoreDeskGroupRequest;
use App\Http\Requests\Configuration\DeskGroup\UpdateDeskGroupRequest;
use App\Services\Configuration\DeskGroup\DeskGroupService;
use Illuminate\Http\Request;

class DeskGroupController extends Controller
{
    public function __construct(private DeskGroupService $deskGroupService) {}

    public function index(DeskGroupsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/desk-groups/index', [
            'deskGroupTypes' => DeskGroupEnum::options(),
        ]);
    }

    public function create() {}

    public function store(StoreDeskGroupRequest $request)
    {
        $this->deskGroupService->create($request->validated());

        return redirect()->back()->with('success', 'Desk group created successfully.');
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateDeskGroupRequest $request, string $id)
    {
        $this->deskGroupService->update($request->validated(), $id);

        return redirect()->route('configuration.desk-groups.index')->with([
            'success' => __('Desk group updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->deskGroupService->delete($id);

        return redirect()->route('configuration.desk-groups.index')->with([
            'success' => __('Desk group deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->deskGroupService->bulkDelete($ids);

        return response()->json(['message' => 'Desk groups deleted successfully.']);
    }
}
