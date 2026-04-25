<?php

namespace App\Services;

use App\Enums\Media\MediaCollectionEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    /**
     * Return the fully qualified Eloquent model class name.
     */
    abstract protected function model(): string;

    protected function newQuery(): Builder
    {
        return $this->model()::query();
    }

    public function findById(int|string $id): Model
    {
        return $this->model()::findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->newQuery()->get();
    }

    public function list(Request $request): LengthAwarePaginator
    {
        return $this->applyFilters($this->newQuery(), $request)
            ->paginate($request->integer('per_page', 15));
    }

    public function export(Request $request): Collection
    {
        return $this->applyFilters($this->newQuery(), $request)->get();
    }

    public function create(array $data): ?Model
    {
        DB::beginTransaction();
        $model = $this->model()::create($data);
        if (isset($data['image'])) {
            $model->addMedia($data['image'])->toMediaCollection(MediaCollectionEnum::ProductCategogy->value);
        }
        DB::commit();

        return $model;
    }

    public function update(Model $model, array $data): ?Model
    {
        DB::beginTransaction();
        $model->update($data);
        if (isset($data['image'])) {
            $model->clearMediaCollection(MediaCollectionEnum::ProductCategogy->value);
            $model->addMedia($data['image'])->toMediaCollection(MediaCollectionEnum::ProductCategogy->value);
        }

        DB::commit();

        return $model->fresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
        $model->clearMediaCollection(MediaCollectionEnum::ProductCategogy->value);
    }

    public function bulkDelete(array $ids): void
    {
        $this->model()::whereIn('id', $ids)->delete();
    }

    /**
     * Override in subclasses to apply model-specific query filters.
     */
    protected function applyFilters(Builder $query, Request $request): Builder
    {
        return $query;
    }
}
