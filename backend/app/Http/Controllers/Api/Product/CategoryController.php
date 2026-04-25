<?php

namespace App\Http\Controllers\Api\Product;

use App\Exports\Product\CategoriesExport;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BulkDeleteCategoryRequest;
use App\Http\Requests\Product\StoreCategoryRequest;
use App\Http\Requests\Product\UpdateCategoryRequest;
use App\Http\Resources\Product\CategoryResource;
use App\Models\Product\Category;
use App\Services\FormatService;
use App\Services\Product\CategoryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly FormatService $formatService,
    ) {}

    public function index(Request $request)
    {
        return CategoryResource::collection($this->categoryService->list($request));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());

        return ApiResponseHelper::success(new CategoryResource($category), 'Category created', 201);
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children']);

        return ApiResponseHelper::success(new CategoryResource($category), 'Category details');
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryService->update($category, $request->validated());

        return ApiResponseHelper::success(new CategoryResource($category), 'Category updated');
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return ApiResponseHelper::success([], 'Category deleted');
    }

    public function bulkDelete(BulkDeleteCategoryRequest $request)
    {
        $this->categoryService->bulkDelete($request->validated()['ids']);

        return ApiResponseHelper::success([], 'Categories deleted');
    }

    public function tree()
    {
        return ApiResponseHelper::success(
            CategoryResource::collection($this->categoryService->tree()),
            'Category tree'
        );
    }

    public function export(Request $request)
    {
        $categories = $this->categoryService->export($request);

        return Excel::download(new CategoriesExport($categories), $this->formatService->exportFileName('categories'));
    }
}
