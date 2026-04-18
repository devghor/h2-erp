<?php

use App\Http\Controllers\Api\Auth\LogingController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Uam\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('login', [LogingController::class, 'store'])->name('login');

    Route::middleware('auth:api')->group(function () {
        /**
         * Uam Module
         */
        Route::prefix('uam')
            ->name('uam.')
            ->group(function () {
                Route::get('me', [UserController::class, 'me'])->name('me');
            });
    });
});
