<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Base\DesignationController;
use App\Http\Controllers\Api\Uam\UserController;
use App\Http\Controllers\Api\Uam\RoleController;
use App\Http\Controllers\Api\Uam\PermissionController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/login', [AuthController::class, 'login']);
        });

    /** Protected Routes **/

    // Auth endpoints that only need Sanctum — no tenant context required
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/uam/me', [AuthController::class, 'me'])->name('uam.me');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });

    Route::middleware(['auth:sanctum', InitializeTenancyByRequestData::class])->group(function () {
        /**
         * Auth Module
         */
        Route::prefix('auth')
            ->name('auth.')
            ->group(function () {
                Route::post('/refresh', [AuthController::class, 'refresh']);
                Route::post('/change-password', [AuthController::class, 'changePassword']);
            });

        /**
         * UAM (User Access Management) Module
         */
        Route::prefix('uam')
            ->name('uam.')
            ->group(function () {

                // User resource routes
                Route::get('users/export', [UserController::class, 'export'])
                    ->name('users.export');
                Route::post('users/bulk-delete', [UserController::class, 'bulkDestroy'])
                    ->name('users.bulk-destroy');
                Route::apiResource('users', UserController::class);

                // Role management
                Route::get('roles/export', [RoleController::class, 'export'])
                    ->name('roles.export');
                Route::post('roles/bulk-delete', [RoleController::class, 'bulkDestroy'])
                    ->name('roles.bulk-destroy');
                Route::apiResource('roles', RoleController::class);
                Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions'])
                    ->name('roles.assign-permissions');

                // Permission management
                Route::get('permissions', [PermissionController::class, 'index'])
                    ->name('permissions.index');
                Route::get('permissions/grouped', [PermissionController::class, 'grouped'])
                    ->name('permissions.grouped');
                Route::get('permissions/user', [PermissionController::class, 'userPermissions'])
                    ->name('permissions.user');
            });
    });
});
