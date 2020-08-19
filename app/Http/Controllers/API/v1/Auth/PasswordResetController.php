<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use App\Services\SmsService;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
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
     * Reset password
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function reset(PasswordResetRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ApiResponse::responseError([], 'We can\'t find a user with that e-mail address.');
        }

        if ($request->type  && $request->type == 'mobile') {
            $response = $this->resetViaOtp($request, $user);
        } else {
            $response = $this->resetViaToken($request);
        }

        if (!$response->status) {
            return ApiResponse::responseError([], $response->message, 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        $this->mailService->sendEmail(
            $user->email,
            "Password reset successfully",
            [
                "introLines" => [
                    "You have successfully, reset your password,",
                    "If you don't make this change yourself, 
                    Kindly send an email to our support mail 'support@adashi.com' "
                ],
                "content" =>   "Thanks, for using Adashi",
                "greeting" => "Hello"
            ]
        );

        $options = [
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
        return ApiResponse::responseSuccess($user->toArray(), 'Password reset successfully', $options);
    }

    public function resetViaToken(Request $request): object
    {
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset) {
            return (object) [
                "status" => false,
                "message" => "This password reset token is invalid."
            ];
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return (object) [
                "status" => false,
                "message" => "This password reset token has expired."
            ];
        }

        $passwordReset->delete();
        return (object) [
            "status" => true,
            "message" => "Your password has been reset"
        ];
    }
    public function resetViaOtp(Request $request, User $user): object
    {
        $validateOtp = $this->otpService->validate(get_class($user), $user->email, $request->token);

        if (!$validateOtp->status) {
            return (object) [
                "status" => false,
                "message" => $validateOtp->message
            ];
        }

        return (object) [
            "status" => true,
            "message" => "Your password has been reset"
        ];
    }
}
