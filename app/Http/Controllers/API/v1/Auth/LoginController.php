<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->login($request);
        if (!$user->status) {
            return ApiResponse::responseError([], $user->message);
        }
        $options = [
            'access_token' => $user->access_token,
            'token_type' => 'Bearer'
        ];
        return ApiResponse::responseSuccess($user->data, $user->message, $options);
    }
}
