<?php

namespace App\Http\Controllers\Product\Category;

use App\DataTables\Product\Category\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Category\StoreCategoryRequest;
use App\Http\Requests\Product\Category\UpdateCategoryRequest;
use App\Models\Product\Category;
use App\Services\Product\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->renderInertia('product/categories/index', [
            'categories' => Category::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function create() {}

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->create($request->validated());

        return redirect()->route('product.categories.index')->with([
            'success' => 'Category created successfully.',
        ]);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $this->categoryService->update($request->validated(), $id);

        return redirect()->route('product.categories.index')->with([
            'success' => 'Category updated successfully.',
        ]);
    }

    public function destroy(string $id)
    {
        $this->categoryService->delete($id);

        return redirect()->route('product.categories.index')->with([
            'success' => 'Category deleted successfully.',
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->categoryService->bulkDelete($ids);

        return response()->json(['message' => 'Categories deleted successfully.']);
    }
}
