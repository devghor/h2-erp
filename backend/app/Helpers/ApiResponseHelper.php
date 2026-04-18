<?php

namespace App\Helpers;

class ApiResponseHelper
{
    /**
     * Success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function success($result, $message, $code = 200)
    {
        $response = [
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }


    /**
     * Error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function error($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
