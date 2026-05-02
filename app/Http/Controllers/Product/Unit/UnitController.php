<?php

namespace App\Http\Controllers\Product\Unit;

use App\DataTables\Product\Unit\UnitsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Unit\StoreUnitRequest;
use App\Http\Requests\Product\Unit\UpdateUnitRequest;
use App\Services\Product\Unit\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct(private UnitService $unitService) {}

    public function index(UnitsDataTable $dataTable)
    {
        return $dataTable->renderInertia('product/units/index');
    }

    public function create() {}

    public function store(StoreUnitRequest $request)
    {
        $this->unitService->create($request->validated());

        return redirect()->route('product.units.index')->with([
            'success' => 'Unit created successfully.',
        ]);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateUnitRequest $request, string $id)
    {
        $this->unitService->update($request->validated(), $id);

        return redirect()->route('product.units.index')->with([
            'success' => 'Unit updated successfully.',
        ]);
    }

    public function destroy(string $id)
    {
        $this->unitService->delete($id);

        return redirect()->route('product.units.index')->with([
            'success' => 'Unit deleted successfully.',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->unitService->bulkDelete($ids);

        return response()->json(['message' => 'Units deleted successfully.']);
    }
}
