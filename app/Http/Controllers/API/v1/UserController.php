<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $response = $this->userService->changePassword($request->convertToDto(), request()->user());
        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }
        return ApiResponse::responseSuccess([], $response->message);
    }
}
