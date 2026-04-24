<?php

use App\Helpers\ApiResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByRequestDataException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Suppress logging for expected client errors
        $exceptions->dontReport([
            ValidationException::class,
            ModelNotFoundException::class,
            AuthenticationException::class,
            AuthorizationException::class,
            NotFoundHttpException::class,
            MethodNotAllowedHttpException::class,
            TenantCouldNotBeIdentifiedByRequestDataException::class,
        ]);

        // Structured logging for database errors (replaces default logging)
        $exceptions->report(function (QueryException $e): false {
            Log::critical('Database error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'code' => $e->getCode(),
            ]);

            return false;
        });

        $exceptions->render(function (ValidationException $e) {
            return ApiResponseHelper::error('Validation failed', 422, $e->errors());
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return ApiResponseHelper::error(class_basename($e->getModel()).' not found', 404);
        });

        $exceptions->render(function (AuthenticationException $_) {
            return ApiResponseHelper::error('Unauthenticated', 401);
        });

        $exceptions->render(function (AuthorizationException $_) {
            return ApiResponseHelper::error('This action is unauthorized', 403);
        });

        $exceptions->render(function (NotFoundHttpException $_) {
            return ApiResponseHelper::error('The requested resource was not found', 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $_) {
            return ApiResponseHelper::error('HTTP method not allowed', 405);
        });

        $exceptions->render(function (QueryException $_) {
            return ApiResponseHelper::error('A database error occurred. Please try again later.', 500);
        });

        $exceptions->render(function (TenantCouldNotBeIdentifiedByRequestDataException $_) {
            return ApiResponseHelper::error('Tenant could not be identified', 404);
        });

        // Catch-all for unexpected server errors
        $exceptions->render(function (Throwable $e) {
            $message = app()->isProduction()
                ? 'An unexpected error occurred. Please try again later.'
                : $e->getMessage();

            return ApiResponseHelper::error($message, 500);
        });
    })->create();
