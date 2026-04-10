<?php

use App\Http\Controllers\Api\Auth\AuthController;
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
         * UAM Module
         */
        Route::prefix('uam')
            ->name('uam.')
            ->group(function () {

                // User management
                Route::prefix('users')->name('users.')->group(function () {
                    Route::get('export/excel', [UserController::class, 'exportExcel'])->name('export.excel');
                    Route::post('bulk-delete', [UserController::class, 'bulkDestroy'])->name('bulk-destroy');
                    Route::apiResource('/', UserController::class)->parameters(['' => 'user']);
                });

                // Role management
                Route::prefix('roles')->name('roles.')->group(function () {
                    Route::get('export', [RoleController::class, 'export'])->name('export');
                    Route::post('bulk-delete', [RoleController::class, 'bulkDestroy'])->name('bulk-destroy');
                    Route::post('{role}/permissions', [RoleController::class, 'assignPermissions'])->name('assign-permissions');
                    Route::apiResource('/', RoleController::class)->parameters(['' => 'role']);
                });

                // Permission management
                Route::prefix('permissions')->name('permissions.')->group(function () {
                    Route::get('/', [PermissionController::class, 'index'])->name('index');
                    Route::get('grouped', [PermissionController::class, 'grouped'])->name('grouped');
                    Route::get('user', [PermissionController::class, 'userPermissions'])->name('user');
                });
            });
    });
});
