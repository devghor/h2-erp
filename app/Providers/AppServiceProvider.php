<?php

namespace App\Providers;

use App\Enums\Uam\GlobalRoleEnum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->regiterGlobalPermissions();
    }

    public function regiterGlobalPermissions(): void
    {
        $globalPermissions = config('global-permission');

        Gate::before(function ($user, $ability) use ($globalPermissions) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            if ($user->isAdmin()) {
                if (!in_array($ability, $globalPermissions[GlobalRoleEnum::Admin->value])) {
                    return true;
                }
            }
        });
    }
}
