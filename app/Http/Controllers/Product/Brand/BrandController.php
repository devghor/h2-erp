<?php

namespace App\Http\Controllers\Product\Brand;

use App\DataTables\Product\Brand\BrandsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Brand\StoreBrandRequest;
use App\Http\Requests\Product\Brand\UpdateBrandRequest;
use App\Services\Product\Brand\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(private BrandService $brandService) {}

    public function index(BrandsDataTable $dataTable)
    {
        return $dataTable->renderInertia('product/brands/index');
    }

    public function create() {}

    public function store(StoreBrandRequest $request)
    {
        $this->brandService->create($request->validated());

        return redirect()->route('product.brands.index')->with([
            'success' => 'Brand created successfully.',
        ]);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateBrandRequest $request, string $id)
    {
        $this->brandService->update($request->validated(), $id);

        return redirect()->route('product.brands.index')->with([
            'success' => 'Brand updated successfully.',
        ]);
    }

    public function destroy(string $id)
    {
        $this->brandService->delete($id);

        return redirect()->route('product.brands.index')->with([
            'success' => 'Brand deleted successfully.',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->brandService->bulkDelete($ids);

        return response()->json(['message' => 'Brands deleted successfully.']);
    }
}
