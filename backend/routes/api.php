<?php

use App\Http\Controllers\Api\Auth\LogingController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Tenancy\TenantController;
use App\Http\Controllers\Api\Uam\UserController;
use App\Http\Middleware\InitializeTenancy;
use App\Http\Middleware\TenantPermission;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::post('register', [RegisterController::class, 'register'])->name('register');
            Route::post('login', [LogingController::class, 'login'])->name('login');
        });

    Route::middleware(['auth:api', InitializeTenancy::class, TenantPermission::class])->group(function () {
        /**
         * Uam Module
         */
        Route::prefix('uam')
            ->name('uam.')
            ->group(function () {
                Route::get('me', [UserController::class, 'me'])->name('me');
                Route::apiResource('users', UserController::class);
            });


        /**
         * Tenancy Module
         */
        Route::prefix('tenancy')
            ->name('tenancy.')
            ->group(function () {
                Route::apiResource('tenants', TenantController::class);
                Route::post('tenants/switch', [TenantController::class, 'switch'])->name('tenants.switch');
            });
    });
});
