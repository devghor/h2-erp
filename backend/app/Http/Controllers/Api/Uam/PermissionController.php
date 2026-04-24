<?php

namespace App\Http\Controllers\Api\Uam;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Uam\PermissionResource;
use App\Models\Uam\Permission;

class PermissionController extends Controller
{
    public function grouped()
    {
        $grouped = Permission::all()
            ->groupBy('module')
            ->map(fn ($modulePerms) => $modulePerms
                ->groupBy('group')
                ->map(fn ($groupPerms) => PermissionResource::collection($groupPerms)->resolve())
            );

        return ApiResponseHelper::success(['permissions' => $grouped], 'Permissions grouped');
    }
}
