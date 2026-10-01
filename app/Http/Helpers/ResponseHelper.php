<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Return JSON response
     *
     * @param int $code
     * @param bool $success
     * @param array $message
     * @return JsonResponse
     */
    public static function returnResponse($code, $success, $message)
    {
        return response()->json([
            'code' => $code,
            'success' => $success,
            'message' => $message
        ], $code);
    }
}