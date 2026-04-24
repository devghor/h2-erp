<?php

namespace App\Models\Uam;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Uam\GlobalRoleEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

#[Fillable(['name', 'email', 'username', 'password', 'company_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements OAuthenticatable
{
    /** @use HasFactory<UserFactory> */
    use BelongsToTenant, HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected string $guard_name = 'web';

    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            $user->ulid ??= (string) Str::ulid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    protected function getDefaultGuardName(): string
    {
        return $this->guard_name;
    }

    public function isSuperAdmin(): bool
    {
        return $this->global_role === GlobalRoleEnum::SuperAdmin->value;
    }
}
