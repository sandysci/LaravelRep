<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\User\CreateRequest;
use App\Services\OtpService;
use App\Services\UserService;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService, OtpService $otpService)
    {
        $this->userService = $userService;
    }

    public function store(CreateRequest $request)
    {
        try {
            $user = $this->userService->register($request->convertToDto());
            if (!$user->status) {
                throw new \Exception($user->message);
            }
            return ApiResponse::responseCreated($user->data, $user->message);
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, $e->getMessage());
        }
    }
}
