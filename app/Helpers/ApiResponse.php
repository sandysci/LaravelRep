<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Throwable;

class ApiResponse
{
    protected static function respond(
        array $data = [],
        string $status = 'success',
        string $message = null,
        int $statusCode = 200,
        array $options = []
    ): JsonResponse {
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
    public static function responseSuccess(
        array $data = [],
        string $message = "Success",
        array $options = []
    ): JsonResponse {
        return self::respond($data, 'success', $message, 200, $options);
    }

    public static function responseCreated(
        array $data = [],
        string $message = "New entity created",
        array $options = []
    ): JsonResponse {
        return self::respond($data, 'success', $message, 201, $options);
    }

    public static function responseNoContent(
        array $data = [],
        string $message = "No content",
        array $options = []
    ): JsonResponse {
        return self::respond($data, 'success', $message, 204, $options);
    }

    public static function responseError(
        array $data = [],
        string $message = "Error encountered",
        int $statusCode = 400,
        array $options = []
    ): JsonResponse {
        return self::respond($data, 'error', $message, $statusCode, $options);
    }

    public static function responseUnauthorized(string $message = 'Unauthorized', array $options = []): JsonResponse
    {
        return self::responseError([], $message, 401, $options);
    }

    public static function responseForbidden(string $message = 'Forbidden', array $options = []): JsonResponse
    {
        return self::responseError([], $message, 403, $options);
    }

    public static function responseValidationError(Validator $validator, string $message = null): JsonResponse
    {
        $data = $validator->errors()->all();
        $error = collect($data)->unique()->first();
        $msg = $message ?? $error;

        return self::responseError($data, $msg, 422);
    }

    /**
     * @param Throwable $exception
     * @param int $statusCode
     * @param string $message
     * @return JsonResponse
     */
    public static function responseException(
        Throwable $exception,
        int $statusCode = 400,
        string $message = "Exception error"
    ): JsonResponse {
        $options = array('trace' => $exception->getTrace());
        if (env('APP_ENV') === 'production') {
            return self::responseError([], $message, $statusCode, []);
        }
        return self::responseError([], $message, $statusCode, $options);
    }
}
