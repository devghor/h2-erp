<?php

namespace App\Services\Product;

use App\Models\Product\Category;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryService extends BaseService
{
    protected function model(): string
    {
        return Category::class;
    }

    protected function newQuery(): Builder
    {
        return Category::with(['parent', 'children']);
    }

    protected function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->input('name')}%"))
            ->when($request->filled('parent_category_id'), fn($q) => $q->where('parent_category_id', $request->input('parent_category_id')))
            ->when($request->boolean('root_only'), fn($q) => $q->whereNull('parent_category_id'))
            ->when($request->filled('from_date'), fn($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn($q) => $q->whereDate('created_at', '<=', $request->input('to_date')));
    }

    public function tree(): Collection
    {
        return Category::with('children.children')
            ->whereNull('parent_category_id')
            ->orderBy('name')
            ->get();
    }
}
