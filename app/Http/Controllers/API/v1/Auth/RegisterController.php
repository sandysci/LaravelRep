<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RegisterRequest;
use App\Services\OtpService;
use App\Services\UserService;

class RegisterController extends ApiController
{
    protected $userService;

    public function __construct(UserService $userService, OtpService $otpService)
    {
        $this->userService = $userService;
    }

    public function store(RegisterRequest $request)
    {
        try {
            $user = $this->userService->register($request);
            if (!$user->status) {
                throw new \Exception($user->message);
            }
            return $this->responseSuccess($user->data, $user->message);
        } catch (\Exception $e) {
            return $this->responseException($e, 400, $e->getMessage());
        }
    }
}
