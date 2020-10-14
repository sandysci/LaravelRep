<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\VerificationRequest;
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
    protected UserService $userService;
    protected OtpService $otpService;
    protected MailService $mailService;
    protected SmsService $smsService;

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

    public function verify(VerificationRequest $request): JsonResponse
    {
        try {
            $dto = $request->convertToDto();
            $user = User::where('email', $dto->email)->with('verificationToken')->first();
            if (!$user) {
                throw new \Exception("Invalid Request.");
            }

            if ($user->email_verified_at || !$user->hasVerificationToken()) {
                return ApiResponse::responseSuccess([], "Account already verified");
            }


            if ($user->verificationToken->token !== $dto->token) {
                return ApiResponse::responseError([], 'Invalid Token');
            }
            if ($user->verificationToken->hasExpired()) {
                throw new \Exception("Token has expired.");
            }

            $user->email_verified_at = Carbon::now()->timestamp;
            $user->save();
            $user->verificationToken->delete();

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
