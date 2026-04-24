<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    public static function success(mixed $result, string $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'data' => $result,
            'message' => $message,
        ], $code);
    }

    public static function error(string $message, int $code = 500, array $errors = []): JsonResponse
    {
        $response = ['message' => $message];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
