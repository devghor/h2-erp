<?php

namespace App\Http\Controllers\Configuration\FunctionAssignment;

use App\DataTables\Configuration\FunctionAssignment\FunctionAssignmentsDataTable;
use App\Enums\Configuration\FunctionAssignment\FunctionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\FunctionAssignment\StoreFunctionAssignmentRequest;
use App\Http\Requests\Configuration\FunctionAssignment\UpdateFunctionAssignmentRequest;
use App\Models\Uam\User;
use App\Services\Configuration\FunctionAssignment\FunctionAssignmentService;
use Illuminate\Http\Request;

class FunctionAssignmentController extends Controller
{
    public function __construct(private FunctionAssignmentService $service) {}

    public function index(FunctionAssignmentsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/function-assignments/index', [
            'users'       => User::select(['id', 'name', 'email'])->get(),
            'typeOptions' => FunctionTypeEnum::options(),
        ]);
    }

    public function create() {}

    public function store(StoreFunctionAssignmentRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->back()->with('success', 'Function assignment created successfully.');
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateFunctionAssignmentRequest $request, string $id)
    {
        $this->service->update($request->validated(), $id);

        return redirect()->route('configuration.function-assignments.index')
            ->with('success', 'Function assignment updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('configuration.function-assignments.index')
            ->with('success', 'Function assignment deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'required',
        ])['ids'];

        $this->service->bulkDelete($ids);

        return response()->json(['message' => 'Function assignments deleted successfully.']);
    }
}
