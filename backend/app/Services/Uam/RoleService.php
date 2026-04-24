<?php

namespace App\Services\Uam;

use App\Models\Uam\Role;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoleService extends BaseService
{
    protected function model(): string
    {
        return Role::class;
    }

    protected function newQuery(): Builder
    {
        return Role::with('permissions');
    }

    protected function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('name'), fn ($q) => $q->where('name', 'like', "%{$request->input('name')}%"))
            ->when($request->filled('description'), fn ($q) => $q->where('description', 'like', "%{$request->input('description')}%"))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to_date')));
    }

    public function create(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
            'description' => $data['description'] ?? null,
        ]);

        return $role->load('permissions');
    }

    public function update(Model $model, array $data): Role
    {
        $model->update($data);

        return $model->load('permissions');
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);

        return $role->load('permissions');
    }

    public function all(): Collection
    {
        return Role::select('id', 'name')->orderBy('name')->get();
    }
}
