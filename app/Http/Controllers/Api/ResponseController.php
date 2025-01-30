<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    /**
     * Generate a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Generate an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $data
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'An error occurred', int $statusCode = 500, $data = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
