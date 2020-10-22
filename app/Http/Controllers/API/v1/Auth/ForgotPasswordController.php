<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }
    /**
     * Create token password reset
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'callback_url' => 'sometimes'
        ]);

        if ($validator->fails()) {
            return ApiResponse::responseValidationError($validator);
        }

        $response = $this->userService->requestPasswordResetToken($request);
        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }
        return ApiResponse::responseSuccess($response->data, $response->message);
    }
}
