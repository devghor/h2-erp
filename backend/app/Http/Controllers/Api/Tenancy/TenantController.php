<?php

namespace App\Http\Controllers\Api\Tenancy;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = auth()->user();

        if ($authUser->isAdmin()) {
            return ApiResponseHelper::success(Tenant::all(), 'Tenants list');
        } else {
            return ApiResponseHelper::success([tenant()], 'Tenant list');
        }
    }

    public function switch(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenantId = $request->input('tenant_id');
        if (auth()->user()->isAdmin()) {
            tenancy()->initialize($tenantId);

            setPermissionsTeamId($tenantId);

            auth()->user()->update(['tenant_id' => $tenantId]);
        }

        return ApiResponseHelper::success(tenant(), 'Switched tenant successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
