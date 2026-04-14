<?php

namespace App\Http\Controllers\Api\Tenancy;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenancy\TenantCollection;
use App\Http\Resources\Tenancy\TenantResource;
use App\Services\Tenancy\TenancyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

class TenancyController extends Controller
{
    public function __construct(
        protected TenancyService $tenancyService
    ) {}

    /**
     * Return all tenants for Superadmin, or only the authenticated user's tenant for others.
     */
    public function index(Request $request): TenantCollection
    {
        $tenants = $this->tenancyService->getTenants($request->user());

        return new TenantCollection($tenants);
    }

    /**
     * Return a single tenant. Superadmin can access any; others only their own.
     */
    public function show(Request $request, string $id): TenantResource|JsonResponse
    {
        $tenant = $this->tenancyService->getTenant($request->user(), $id);

        if (!$tenant) {
            return response()->json(['message' => 'You do not have access to this tenant.'], Response::HTTP_FORBIDDEN);
        }

        return new TenantResource($tenant);
    }

    /**
     * Switch the active tenant (SuperAdmin only).
     */
    public function switch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        try {
            $result = $this->tenancyService->switchTenant($request->user(), $validated['tenant_id']);
        } catch (UnauthorizedException $e) {
            return ApiResponse::unauthorized($e->getMessage());
        }

        return ApiResponse::success('Tenant switched successfully', $result);
    }
}
