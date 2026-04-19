<?php

use App\Helpers\ApiResponseHelper;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByRequestDataException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * Validation Exception
         */
        $exceptions->render(function (ValidationException $e, $request) {
            return ApiResponseHelper::error([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        });

        /**
         * Tenant Could Not Be Identified By Request Data Exception
         */
        $exceptions->render(function (TenantCouldNotBeIdentifiedByRequestDataException $e, $request) {
            return ApiResponseHelper::error('Tenant could not be identified', 404);
        });
    })->create();
