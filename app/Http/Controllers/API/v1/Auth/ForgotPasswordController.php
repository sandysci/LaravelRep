<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\ApiController;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class ForgotPasswordController extends ApiController
{
    protected $mailService;
    protected $smsService;
    protected $otpService;

    public function __construct(
        MailService $mailService,
        SmsService $smsService,
        OtpService $otpService
    ) {
        $this->mailService = $mailService;
        $this->smsService = $smsService;
        $this->otpService = $otpService;
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
            return $this->responseValidationError($validator);
        }

        $response = $this->userService->requestPasswordResetToken($request);
        if (!$response->status) {
            return $this->responseError([], $response->message);
        }
        return $this->responseSuccess($response->data, $response->message);
    }
}
