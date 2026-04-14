<?php

namespace App\Services\Tenancy;

use App\Enums\Uam\RoleEnum;
use App\Http\Resources\Tenancy\TenantResource;
use App\Models\Tenancy\Tenant;
use App\Models\Uam\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\UnauthorizedException;

class TenancyService
{

    public function switchTenant(User $user, string $tenantId): array
    {
        if (!$user->hasRole(RoleEnum::SuperAdmin->name)) {
            throw new UnauthorizedException('Only SuperAdmin can switch tenants.');
        }

        $tenant = Tenant::findOrFail($tenantId);

        tenancy()->initialize($tenant);

        return [
            'tenant' => new TenantResource($tenant),
        ];
    }

    public function getTenants(User $user): Collection
    {
        if ($user->hasRole(RoleEnum::SuperAdmin->name)) {
            return Tenant::all();
        }

        if ($user->tenant_id) {
            return Tenant::where('id', $user->tenant_id)->get();
        }

        return new Collection();
    }

    public function getTenant(User $user, string $id): ?Tenant
    {
        if ($user->hasRole(RoleEnum::SuperAdmin->name)) {
            return Tenant::findOrFail($id);
        }

        if ($user->tenant_id === $id) {
            return Tenant::findOrFail($id);
        }

        return null;
    }
}
