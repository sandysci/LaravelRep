<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Responses
    public function successResponse($data = null, $message) {
        return response()->json([
            'status' => 'success',
            'data' => isset($data) ? $data : [],
            'message' => $message
        ], 200);
    }

    public function errorResponse($data = null, $message) {
        return response ()->json ([
            'status' => 'error',
            'data' => isset($data) ? $data : [],
            'message' => $message,
        ], 400);
    }

    public function validationError($validator, $message = null) {
            $data = $validator->errors()->all();
            $error = collect($data)->unique()->first();
    
            return response ()->json ([
                'status' => 'error',
                'data' => $data,
                'message' => $message ?? $error,
            ], 422);
    }
}
