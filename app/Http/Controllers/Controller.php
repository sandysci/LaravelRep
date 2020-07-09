<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    // Responses
    protected function respond(array $data = [], string $status = 'success', string $message = null, int $statusCode = 200, array $options = []): JsonResponse
    {
        $response = [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];
        if (count($options) > 0) {
            foreach ($options as $key => $value) {
                $response[$key] = $value;
            }
        }

        return response()->json($response, $statusCode);
    }
    protected function responseSuccess(array $data = [], string $message = "Success", array $options = []): JsonResponse
    {
        return $this->respond($data, 'success', $message, 200, $options);
    }

    protected function responseCreated(array $data = [], string $message = "New entity created", array $options = []): JsonResponse
    {
        return $this->respond($data, 'success', $message, 201, $options);
    }

    protected function responseNoContent(array $data = [], string $message = "No content", array $options = []): JsonResponse
    {
        return $this->respond($data, 'success', $message, 204, $options);
    }
    protected function responseError(array $data = [], string $message = "Error encountered", int $statusCode = 400, array $options = []): JsonResponse
    {
        return $this->respond($data, 'error', $message, $statusCode, $options);
    }

    protected function responseUnauthorized(string $message = 'Unauthorized', array $options = []): JsonResponse
    {
        return $this->responseError([], $message, 401, $options);
    }

    protected function responseFobidden(string $message = 'Forbidden', array $options = []): JsonResponse
    {
        return $this->responseError([], $message, 403, $options);
    }

    protected function responseValidationError(Validator $validator, string $message = null): JsonResponse
    {
        $data = $validator->errors()->all();
        $error = collect($data)->unique()->first();
        $msg = $message ?? $error;

        return $this->responseError($data, $msg, 422);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    protected function responseException(Throwable $exception, $statusCode = 400, string $message = "Exception error"): JsonResponse
    {
        $options = array('trace' => $exception->getTrace());
        if (env('APP_ENV') === 'production') {
            return $this->responseError([], $message, $statusCode, []);
        }

        return $this->responseError([], $message, $statusCode, $options);
    }
}
