<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Responses
    protected function respond(array $data = [], string $status = 'success', string $message = null, int $statusCode = 200, array $options= []) {
        $response = [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];
        if(count($options) > 0) {
            foreach ($options as $key  => $value) {
                $response[$key] = $value;
            }
        }
    
         return response ()->json ($response, $statusCode);
    }
    protected function responseSuccess(array $data = [], string $message, $options = []) {
        return $this->respond($data, 'success', $message,200, $options);
    }

    protected function responseCreated(array $data = [], string $message, $options = []) {
        return $this->respond($data, 'success', $message, 201, $options);
    }

    protected function responseNoContent(array $data = [], string $message, $options = []) {
        return $this->respond($data, 'success', $message, 204, $options);
    }
    protected function responseError(array $data = [], string $message, int $statusCode = 400, $options = []) {
        return $this->respond ($data, 'error', $message, $statusCode, $options);
    }

    protected function responseUnauthorized(string $message = 'Unauthorized', $options = []) {
        return $this->responseError([], $message, 401, $options);
    }

    protected function responseFobidden($message = 'Forbidden', $options = []) {
        return $this->responseError([], $message, 403, $options);
    }

    protected function responseValidationError($validator, $message = null) {
        $data = $validator->errors()->all();
        $error = collect($data)->unique()->first();
        $msg = $message ?? $error;
        
        return $this->responseError($data, $msg, 422);
    }

        /**
     * @param Exception $exception
     * @return JsonResponse
     */
    protected function responseException(Throwable $exception, $statusCode= 400 , $message): JsonResponse
    {
        $options = array ('trace' => $exception->getTrace());
        if (env('APP_ENV') === 'production') {
            return $this->responseError([], $message, $statusCode, []);
        }
        
        return $this->responseError([], $message, $statusCode, $options);
    }

}
