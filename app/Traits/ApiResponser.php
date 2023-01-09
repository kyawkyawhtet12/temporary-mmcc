<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' =>   $data
        ], $code);
    }

    protected function errorResponse($message = null, $code = 400)
    {
        return response()->json([
            'status'=> 'error',
            'message' => $message,
        ], $code);
    }
}
