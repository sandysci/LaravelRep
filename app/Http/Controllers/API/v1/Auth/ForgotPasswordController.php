<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MailService;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class ForgotPasswordController extends Controller
{
    protected $mailService;
    protected $smsService;

    public function __construct(
        MailService $mailService,
        SmsService $smsService
    ){
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }
     /**
     * Create token password reset
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function create(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'callback_url' => 'sometimes'
            ]);

        if ($validator->fails()) { return $this->responseValidationError($validator);}

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->responseError([], 'We can\'t find a user with that e-mail address.');
        }
        
        if($request->type  && $request->type == 'mobile') {
            $otp = $this->otpService->create ($user->email, 6, 30);
            $this->smsService->sendSms(
                $user->phone, 
                "Adashi: Your OTP is " . $otp->token, 
                "ADASHI"
            );
            return $this->responseSuccess([], 'We have send your password reset token!');
        }

        $callback_url = preg_replace('{/$}', '', $request->callback_url);

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email],[
                'email' => $user->email,
                'token' => Str::random(60)
             ]);
        if ($passwordReset) {
            $this->mailService->sendEmail(
                $user->email, 
                    "Password Reset Request", [
                        "introLines" => [ "Kindly, click the link below to reset your password" ],
                        "content" =>   "Thanks, for using Adashi", 
                        "greeting" => "Hello",
                        "level" => "primary",
                        "actionUrl" => $callback_url . "?token=" .$passwordReset->token,
                        "actionText" => "Click to reset your password"
                    ]
            );
            return $this->responseSuccess([], 'We have e-mailed your password reset link!');
        }
        return $this->responseError([], 'Unreacheable error!', 400);
    }
}
