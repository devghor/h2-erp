<?php

namespace App\Http\Controllers\Product\Product;

use App\DataTables\Product\Product\ProductsDataTable;
use App\Enums\Product\BarcodeSymbologyEnum;
use App\Enums\Product\DurationTypeEnum;
use App\Enums\Product\ProfitMarginTypeEnum;
use App\Enums\Product\ProductTaxEnum;
use App\Enums\Product\ProductTypeEnum;
use App\Enums\Product\TaxMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Product\StoreProductRequest;
use App\Http\Requests\Product\Product\UpdateProductRequest;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Services\Product\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
    ) {}

    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->renderInertia('product/products/index', [
            'categories' => Category::select('id', 'name')->orderBy('name')->get(),
            'brands'     => Brand::select('id', 'name')->orderBy('name')->get(),
            'types'      => ProductTypeEnum::options(),
        ]);
    }

    public function create()
    {
        return inertia('product/products/create', $this->formProps());
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create($request->validated());

        return redirect()->route('product.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        $product = $this->productService->find($id);
        $product->load(['comboItems.itemProduct']);
        $product->append('image_url');

        return inertia('product/products/edit', array_merge(
            $this->formProps(),
            ['product' => $product]
        ));
    }

    public function edit(string $id) {}

    public function update(UpdateProductRequest $request, string $id)
    {
        $this->productService->update($request->validated(), $id);

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->productService->delete($id);

        return redirect()->route('product.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->productService->bulkDelete($ids);

        return response()->json(['message' => 'Products deleted successfully.']);
    }

    private function formProps(): array
    {
        return [
            'categories'         => Category::select('id', 'name')->orderBy('name')->get(),
            'brands'             => Brand::select('id', 'name')->orderBy('name')->get(),
            'units'              => Unit::select('id', 'name', 'code')->orderBy('name')->get(),
            'allProducts'        => Product::select('id', 'name', 'product_cost')->orderBy('name')->get(),
            'productTypes'       => ProductTypeEnum::options(),
            'barcodeSymbologies' => BarcodeSymbologyEnum::options(),
            'productTaxes'       => ProductTaxEnum::options(),
            'taxMethods'         => TaxMethodEnum::options(),
            'profitMarginTypes'  => ProfitMarginTypeEnum::options(),
            'durationTypes'      => DurationTypeEnum::options(),
        ];
    }
}
