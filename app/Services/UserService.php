<?php

namespace App\Services;

use App\Domain\Dto\Request\User\CreateDto;
use App\Domain\Dto\Value\User\UserServiceResponseDto;
use App\Helpers\PhoneNumber;
use App\Models\PasswordReset;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Str;

class UserService
{
    protected $otpService;
    protected $mailService;
    protected $smsService;

    public function __construct(
        OtpService $otpService,
        MailService $mailService,
        SmsService $smsService
    ) {
        $this->otpService = $otpService;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    public function login($request): UserServiceResponseDto
    {
        if (is_numeric($request->get('email'))) {
            $credentials = [
                'phone' => $request->get('email'),
                'password' => $request->get('password')
            ];
        } elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
        } else {
            $credentials = $request->only('email', 'password');
        }

        if (!Auth::attempt($credentials)) {
            return new UserServiceResponseDto(false, "Wrong Login Credentials");
        }
        $user = $request->user();
        // if ($user->email_verified_at === null) {
        //     return new UserServiceResponseDto(false, "Please verify your account");
        // }
        
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        if (!$user->hasAnyRole(['user', 'admin', 'super-admin'])) {
            $request->user()->token()->revoke();
            return new UserServiceResponseDto(false, "User logout");
        }

        $userInfo = User::where('id', $user->id)->with(
            'userProfile',
            'wallet',
            'savingCycle',
            'bankDetail'
        )->first();

        return new UserServiceResponseDto(true, 'User logged in', $userInfo->toArray(), $tokenResult, 'Bearer');
    }

    public function register(CreateDto $request): UserServiceResponseDto
    {
        DB::beginTransaction();
        try {
            $callback_url = preg_replace('{/$}', '', $request->callback_url);

            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_country = $request->phone_country;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->assignRole('user');

            if ($request->type && $request->type == 'mobile') {
                $otp = $this->otpService->create(get_class($user), $user->email, 6, 30);
                $this->smsService->sendSms(
                    $user->phone,
                    "Adashi: Your OTP is " . $otp->token,
                    "ADASHI"
                );
            } else {
                $token = $user->createToken('authToken')->plainTextToken;
                $token = 
                $this->mailService->sendEmail(
                    $user->email,
                    "Verify your account",
                    [
                        "introLines" => ["Kindly, click the link below to activate your account"],
                        "content" =>   "Thanks, for using Adashi",
                        "greeting" => "Hello",
                        "level" => "primary",
                        "actionUrl" => $callback_url . "?token=" . $token,
                        "actionText" => "Click to verify your account"
                    ]
                );
            }
            DB::commit();

            $userInfo = User::where('id', $user->id)->first();

            return new UserServiceResponseDto(true, "Account was created successfully", $userInfo->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return new UserServiceResponseDto(false, $e->getMessage());
        }
    }

    public function update($conds, $request): UserServiceResponseDto
    {
        DB::beginTransaction();
        try {
            $user = User::where($conds)
                ->update($request);
            DB::commit();
            return new UserServiceResponseDto(true, "Account was updated successfully", $user->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return new UserServiceResponseDto(false, $e->getMessage());
        }
    }


    public function findOne($conds): UserServiceResponseDto
    {
        $user = User::where($conds)
            ->with(
                'userProfile',
                'wallet',
                'savingCycle',
                'bankDetail'
            )->first();
        if (!$user) {
            return new UserServiceResponseDto(false, "No account found");
        }

        return new UserServiceResponseDto(true, "Account details", $user->toArray());
    }

    public function verifyViaOTP($request): UserServiceResponseDto
    {
        $email = $request->email;
        $otp = $request->otp;
        $user = User::where("email", $email)
            ->with(
                'userProfile',
                'wallet',
                'savingCycle',
                'bankDetail'
            )->first();

        if (!$user) {
            return new UserServiceResponseDto(false, "User doesn't exit on this platform");
        }
        //Verify OTP
        $checkOtp = $this->otpService->validate(get_class($user), $user->email, $otp);
        if (!$checkOtp->status) {
            return new UserServiceResponseDto(false, $checkOtp->message);
        }

        $user->email_verified_at = Carbon::now()->timestamp;
        $user->save();

        return new UserServiceResponseDto(true, $checkOtp->message, $user->toArray());
    }

    public function resendCode($request): UserServiceResponseDto
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return new UserServiceResponseDto(false, "User's doesn't exist on this platform");
        }

        if (!is_null($user->email_verified_at)) {
            return new UserServiceResponseDto(false, "Account aleady verified");
        }

        if ($request->type && $request->type == 'mobile') {
            $otp = $this->otpService->create(get_class($user), $user->email, 6, 30);

            $this->smsService->sendSms(
                $user->phone,
                "Adashi: Your OTP is " . $otp->token,
                "ADASHI"
            );
            return new UserServiceResponseDto(true, "OTP sent");
        }

        if (!$request->callback_url) {
            return new UserServiceResponseDto(false, "Add a callback URL");
        }

        $callback_url = preg_replace('{/$}', '', $request->callback_url);
        $token = $user->createToken('authToken')->plainTextToken;

        $this->mailService->sendEmail(
            $user->email,
            "Verify your account",
            [
                "introLines" => ["Kindly, click the link below to activate your account"],
                "content" =>   "Thanks, for using Adashi",
                "greeting" => "Hello " . $user->name . ",",
                "level" => "primary",
                "actionUrl" => $callback_url . "?token=" . $token,
                "actionText" => "Click to verify your account"
            ]
        );
        return new UserServiceResponseDto(true, "Token sent");
    }

    public function requestPasswordResetToken(Request $request): UserServiceResponseDto
    {
        $callback_url = preg_replace('{/$}', '', $request->callback_url);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return new UserServiceResponseDto(false, 'We can\'t find a user with that e-mail address.');
        }

        if ($request->type  && $request->type === 'mobile') {
            $otp = $this->otpService->create($user->email, 6, 30);
            $this->smsService->sendSms(
                $user->phone,
                "Adashi: Your OTP is " . $otp->token,
                "ADASHI"
            );
            return new UserServiceResponseDto(true, 'We have send your password reset token!');
        }


        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
            'email' => $user->email,
            'token' => Str::random(60)
        ]);
        if ($passwordReset) {
            $this->mailService->sendEmail(
                $user->email,
                "Password Reset Request",
                [
                    "introLines" => ["Kindly, click the link below to reset your password"],
                    "content" =>   "Thanks, for using Adashi",
                    "greeting" => "Hello",
                    "level" => "primary",
                    "actionUrl" => $callback_url . "?token=" . $passwordReset->token,
                    "actionText" => "Click to reset your password"
                ]
            );

            return new UserServiceResponseDto(true, 'We have e-mailed your password reset link!');
        }
        return new UserServiceResponseDto(false, 'Unreachable error!');
    }
}
