<?php

use App\Http\Controllers\Api\Auth\LogingController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Configuration\CompanyController;
use App\Http\Controllers\Api\Uam\PermissionController;
use App\Http\Controllers\Api\Uam\RoleController;
use App\Http\Controllers\Api\Uam\UserController;
use App\Http\Middleware\CompanyPermission;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::post('register', [RegisterController::class, 'register'])->name('register');
            Route::post('login', [LogingController::class, 'login'])->name('login');
        });

    Route::middleware(['auth:api', InitializeTenancyByRequestData::class, CompanyPermission::class])->group(function () {
        /**
         * Uam Module
         */
        Route::prefix('uam')
            ->name('uam.')
            ->group(function () {
                Route::get('me', [UserController::class, 'me'])->name('me');
                Route::get('users/all', [UserController::class, 'all'])->name('users.all');
                Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
                Route::get('users/export', [UserController::class, 'export'])->name('users.export');
                Route::apiResource('users', UserController::class);
                Route::get('roles/all', [RoleController::class, 'all'])->name('roles.all');
                Route::post('roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
                Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
                Route::apiResource('roles', RoleController::class);
                Route::get('permissions/grouped', [PermissionController::class, 'grouped'])->name('permissions.grouped');
            });

        /**
         * Configuration Module
         */
        Route::prefix('configuration')
            ->name('configuration.')
            ->group(function () {
                Route::post('companies/switch', [CompanyController::class, 'switch'])->name('companies.switch');
                Route::apiResource('companies', CompanyController::class);
            });
    });
});
