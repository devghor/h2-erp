<?php

namespace App\Services\Uam;

use App\Models\Uam\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected function model(): string
    {
        return User::class;
    }

    protected function newQuery(): Builder
    {
        return User::with('roles');
    }

    protected function applyFilters(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('name'), fn ($q) => $q->where('name', 'like', "%{$request->input('name')}%"))
            ->when($request->filled('email'), fn ($q) => $q->where('email', 'like', "%{$request->input('email')}%"))
            ->when($request->filled('username'), fn ($q) => $q->where('username', 'like', "%{$request->input('username')}%"))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to_date')));
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->load('roles');
    }

    public function update(Model $model, array $data): User
    {
        $payload = array_filter([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'username' => $data['username'] ?? null,
        ], fn ($v) => $v !== null);

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $model->update($payload);

        return $model->load('roles');
    }

    public function syncRoles(User $user, array $roles): User
    {
        $user->syncRoles($roles);

        return $user->load('roles');
    }

    public function bulkDelete(array $ulids): void
    {
        DB::transaction(function () use ($ulids): void {
            User::whereIn('ulid', $ulids)->delete();
        });
    }

    public function all(): Collection
    {
        return User::select('id', 'ulid', 'name', 'email')->orderBy('name')->get();
    }
}
