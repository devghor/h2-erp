<?php

namespace App\Http\Controllers\Api\Uam;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Uam\RoleResource;
use App\Models\Uam\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')
            ->when($request->filled('name'), fn ($q) => $q->where('name', 'like', "%{$request->input('name')}%"))
            ->when($request->filled('description'), fn ($q) => $q->where('description', 'like', "%{$request->input('description')}%"))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to_date')))
            ->paginate($request->integer('per_page', 15));

        return RoleResource::collection($roles);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $role = Role::create([
            'name' => $input['name'],
            'guard_name' => 'web',
            'description' => $input['description'] ?? null,
        ]);

        $role->load('permissions');

        return ApiResponseHelper::success(new RoleResource($role), 'Role created', 201);
    }

    public function show(Role $role)
    {
        $role->load('permissions');

        return ApiResponseHelper::success(new RoleResource($role), 'Role details');
    }

    public function all()
    {
        $roles = Role::select('id', 'name')->orderBy('name')->get();

        return ApiResponseHelper::success(['roles' => $roles], 'All roles');
    }

    public function update(Request $request, Role $role)
    {
        if ($request->has('permissions')) {
            $input = $request->validate([
                'permissions' => ['required', 'array'],
                'permissions.*' => ['string', 'exists:permissions,name'],
            ]);

            $role->syncPermissions($input['permissions']);
            $role->load('permissions');

            return ApiResponseHelper::success(new RoleResource($role), 'Permissions updated');
        }

        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $role->update($input);
        $role->load('permissions');

        return ApiResponseHelper::success(new RoleResource($role), 'Role updated');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return ApiResponseHelper::success([], 'Role deleted');
    }

    public function bulkDelete(Request $request)
    {
        $input = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:roles,id'],
        ]);

        Role::whereIn('id', $input['ids'])->delete();

        return ApiResponseHelper::success([], 'Roles deleted');
    }

    public function export(Request $request)
    {
        $roles = Role::with('permissions')
            ->when($request->filled('name'), fn ($q) => $q->where('name', 'like', "%{$request->input('name')}%"))
            ->when($request->filled('description'), fn ($q) => $q->where('description', 'like', "%{$request->input('description')}%"))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to_date')))
            ->get();

        $filename = 'roles_'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($roles) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Description', 'Permissions', 'Created At']);

            foreach ($roles as $role) {
                fputcsv($handle, [
                    $role->id,
                    $role->name,
                    $role->description ?? '',
                    $role->permissions->pluck('name')->join(', '),
                    $role->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
