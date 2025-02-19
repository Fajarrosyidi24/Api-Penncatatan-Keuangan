<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse;

trait APiResponsTrait
{
    public function successResponse($data, $message = "Success", $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Format response error
     */
    public function errorResponse($message, $status = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $status);
    }
}
