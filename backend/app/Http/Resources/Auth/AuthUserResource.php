<?php

namespace App\Http\Resources\Auth;

use App\Enums\Uam\PermissionEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        tenancy()->initialize($this->company_id);

        setPermissionsTeamId(tenant('id'));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->company_id,
            'access_token' => $this->createToken('authToken')->accessToken,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->isSuperAdmin() ? PermissionEnum::cases() : $this->getPermissionsViaRoles()->pluck('name'),
        ];
    }
}
