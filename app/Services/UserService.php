<?php

namespace App\Services;

use App\Domain\Dto\Value\User\UserServiceResponseDto;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Log;

class UserService
{
    protected $user;
    protected $otpService;
    protected $mailService;
    protected $smsService;

    public function __construct(
        User $user,
        OtpService $otpService,
        MailService $mailService,
        SmsService $smsService
    ) {
        $this->user = $user;
        $this->otpService = $otpService;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    public function login($request): UserServiceResponseDto
    {
        // if(is_numeric($request->get('email'))){
        //     return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
        //   }
        //   elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
        //     return ['email' => $request->get('email'), 'password'=>$request->get('password')];
        //   }

        $credentials = $request->only('email', 'password');
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

        return new UserServiceResponseDto(true, 'User logged in', $user->toArray(), $tokenResult, 'Bearer');
    }

    public function register($request): object
    {
        DB::beginTransaction();
        try {
            $callback_url = preg_replace('{/$}', '', $request->callback_url);
            $user = $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            $user->assignRole('user');

            if ($request->type && $request->type == 'mobile') {
                $otp = $this->otpService->create(get_class($user), $user->email, 6, 30);

                if ($user->phone) {
                    $this->smsService->sendSms(
                        $user->phone,
                        "Adashi: Your OTP is " . $otp->token,
                        "ADASHI"
                    );
                } else {
                    $this->mailService->sendEmail(
                        $user->email,
                        "Your OTP from Adashi",
                        [
                            "content" => "Your OTP is " . $otp->token,
                            "greeting" => "Welcome"
                        ]
                    );
                }
            } else {
                $token = $user->createToken('authToken')->plainTextToken;
                $mailStatus = $this->mailService->sendEmail(
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
                Log::info('Mail status: ' . $mailStatus);
            }
            DB::commit();
            return new UserServiceResponseDto(true, "Account was created successfully", $user->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return new UserServiceResponseDto(false, $e->getMessage());
        }
    }

    public function update($conds, $request): UserServiceResponseDto
    {
        DB::beginTransaction();
        try {
            $user = $this->user->where($conds)
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
        $user = $this->user->where($conds)->first();
        if (!$user) {
            return new UserServiceResponseDto(false, "No account found");
        }

        return new UserServiceResponseDto(true, "Account details", $user->toArray());
    }

    public function verifyViaOTP($request): UserServiceResponseDto
    {
        $email = $request->email;
        $otp = $request->otp;
        $user = $this->user->where("email", $email)->first();

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

        $user = $this->user->where('email', $request->email)->first();

        if (!$user) {
            return new UserServiceResponseDto(false, "User's doesn't exist on this platform");
        }

        if ($user->email_verified_at) {
            return new UserServiceResponseDto(false, "Account aleady verified");
        }

        $user->email_verified_at = null;
        $user->save();

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

    public function requestPasswordResetToken($request): UserServiceResponseDto
    {
        return (object) [];
    }
}
