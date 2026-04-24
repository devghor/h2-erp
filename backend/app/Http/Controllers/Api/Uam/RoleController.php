<?php

namespace App\Http\Controllers\Api\Uam;

use App\Exports\Uam\RolesExport;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Uam\BulkDeleteRoleRequest;
use App\Http\Requests\Uam\StoreRoleRequest;
use App\Http\Requests\Uam\UpdateRoleRequest;
use App\Http\Resources\Uam\RoleResource;
use App\Models\Uam\Role;
use App\Services\FormatService;
use App\Services\Uam\RoleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService,
        private readonly FormatService $formatService,
    ) {}

    public function index(Request $request)
    {
        return RoleResource::collection($this->roleService->list($request));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());

        return ApiResponseHelper::success(new RoleResource($role), 'Role created', 201);
    }

    public function show(Role $role)
    {
        $role->load('permissions');

        return ApiResponseHelper::success(new RoleResource($role), 'Role details');
    }

    public function all()
    {
        return ApiResponseHelper::success(['roles' => $this->roleService->all()], 'All roles');
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($request->has('permissions')) {
            $role = $this->roleService->syncPermissions($role, $request->validated()['permissions']);

            return ApiResponseHelper::success(new RoleResource($role), 'Permissions updated');
        }

        $role = $this->roleService->update($role, $request->validated());

        return ApiResponseHelper::success(new RoleResource($role), 'Role updated');
    }

    public function destroy(Role $role)
    {
        $this->roleService->delete($role);

        return ApiResponseHelper::success([], 'Role deleted');
    }

    public function bulkDelete(BulkDeleteRoleRequest $request)
    {
        $this->roleService->bulkDelete($request->validated()['ids']);

        return ApiResponseHelper::success([], 'Roles deleted');
    }

    public function export(Request $request)
    {
        $roles = $this->roleService->export($request);

        return Excel::download(new RolesExport($roles), $this->formatService->exportFileName('roles'));
    }
}
