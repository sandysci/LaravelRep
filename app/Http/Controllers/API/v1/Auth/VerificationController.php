<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use App\Services\SmsService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    protected $userService;
    protected $otpService;
    protected $mailService;
    protected $smsService;

    public function __construct(
        UserService $userService,
        OtpService $otpService,
        MailService $mailService,
        SmsService $smsService
    ) {
        $this->userService = $userService;
        $this->otpService = $otpService;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            $user = User::find(request()->user()->id);
            if (!$user) {
                throw new \Exception("This activation token is invalid or has expired.");
            }

            if ($user->email_verified_at) {
                return ApiResponse::responseSuccess([], "Account already verified");
            }

            $user->email_verified_at = Carbon::now()->timestamp;
            $user->save();

            $this->mailService->sendEmail(
                $user->email,
                "Account verified successfully",
                [
                    "introLines" => [
                        "You have successfully, verified your account,",
                        "Welcome to Adashi"
                    ],
                    "content" =>   "Thanks, for using Adashi",
                    "greeting" => "Hello " . $user->name . ","
                ]
            );
            return ApiResponse::responseSuccess($user->toArray(), "Your email has been verified");
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, $e->getMessage());
        }
    }
    public function verifyOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'otp' => 'required'
            ]);

            if ($validator->fails()) {
                return ApiResponse::responseValidationError($validator);
            }

            $user = $this->userService->verifyViaOTP($request);

            if (!$user->status) {
                return ApiResponse::responseError([], $user->message);
            }

            $this->mailService->sendEmail(
                $user->data["email"],
                "Account verified successfully",
                [
                    "introLines" => [
                        "You have successfully, verified your account,",
                        "Welcome to Adashi"
                    ],
                    "content" =>   "Thanks, for using Adashi",
                    "greeting" => "Hello " . $user->data["name"] . ","
                ]
            );
            return ApiResponse::responseSuccess($user->data, $user->message);
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, $e->getMessage());
        }
    }

    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'callback_url' => 'sometimes'
        ]);
        if ($validator->fails()) {
            return ApiResponse::responseValidationError($validator);
        }
        $user = $this->userService->resendCode($request);
        if (!$user->status) {
            return ApiResponse::responseError([], $user->message, 400);
        }

        return ApiResponse::responseSuccess([], $user->message);
    }
}
