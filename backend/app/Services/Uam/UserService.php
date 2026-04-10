<?php

namespace App\Services\Uam;

use App\Models\Uam\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers(Request $request): LengthAwarePaginator
    {
        $query = User::query();

        if ($request->filled('ulid')) {
            $query->where('ulid', $request->input('ulid'));
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', 'like', '%' . $request->input('tenant_id') . '%');
        }

        $this->applyFilters($query, $request->only(['name', 'email', 'from_date', 'to_date']));

        if ($request->filled('sort_by')) {
            $sortBy    = $request->input('sort_by');
            $sortOrder = $request->input('sort_order', 'asc');

            $allowedSorts = ['ulid', 'name', 'email', 'tenant_id', 'created_at', 'updated_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->input('per_page', 15));
    }

    public function getExportQuery(array $filters): Builder
    {
        $query = User::query()->with('roles');

        $this->applyFilters($query, $filters);

        return $query->orderBy('created_at', 'desc');
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['tenant_id'] = tenant('id');

        return User::create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function bulkDeleteUsers(array $ulids): int
    {
        return User::whereIn('ulid', $ulids)->delete();
    }
}
