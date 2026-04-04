<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    public static function success(string $message = 'Success', array $data = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status ?? Response::HTTP_OK);
    }

    public static function created(string $message = 'Resource created successfully', array $data = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status ?? Response::HTTP_CREATED);
    }

    public static function error(string $message = 'An error occurred', array $errors = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status ?? Response::HTTP_BAD_REQUEST);
    }

    public static function validationError(array $errors = [], string $message = 'Validation failed', ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status ?? Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function unauthorized(string $message = 'Unauthorized', array $errors = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status ?? Response::HTTP_FORBIDDEN);
    }

    public static function unauthenticated(string $message = 'Unauthenticated', array $errors = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status ?? Response::HTTP_UNAUTHORIZED);
    }

    public static function notFound(string $message = 'Resource not found', array $errors = [], ?int $status = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status ?? Response::HTTP_NOT_FOUND);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
