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
    protected function respond(array $data = [], string $status = 'success', string $message = null, int $statusCode = 200, $headers= []) {
        return response ()->json ([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ], $statusCode, $headers);
    }
    protected function responseSuccess(array $data = [], string $message) {
        return $this->respond($data, 'success', $message);
    }

    protected function responseCreated(array $data = [], string $message) {
        return $this->respond($data, 'success', $message, 201);
    }

    protected function responseNoContent(array $data = [], string $message) {
        return $this->respond($data, 'success', $message, 204);
    }
    protected function responseError(array $data = [], string $message, int $statusCode = 400) {
        return $this->respond ($data, 'error',$message,$statusCode);
    }

    protected function respondUnauthorized(string $message = 'Unauthorized') {
        return $this->respondError([], $message, 401);
    }

    protected function respondFobidden($message = 'Forbidden') {
        return $this->respondError([], $message, 403);
    }

     protected function respondValidationError($validator, $message = null) {
        $data = $validator->errors()->all();
        $error = collect($data)->unique()->first();
        $msg = $message ?? $error;
        
        $this->respondError($data, $msg, 422);
    }
}
