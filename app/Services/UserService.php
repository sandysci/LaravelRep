<?php

namespace App\Services;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Log;

class UserService {
    protected $user;
    protected $otpService;
    protected $mailService;
    protected $smsService;

    public function __construct(
        User $user, 
        OtpService $otpService,
        MailService $mailService,
        SmsService $smsService
    ){
        $this->user = $user;
        $this->otpService = $otpService;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    public function login($request): Object {
        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials)){
            return (object) [
                "status" => false,
                "message" => "Wrong Login Credentials"
            ];
        }
        $user = $request->user();
        if($user->email_verified_at === null) {
            return (object) [
                "status" => false,
                "message" => "Please verify your account"
            ];
        }

        $tokenResult = $user->createToken ('authToken')->plainTextToken;

        if (!$user->hasAnyRole(['user', 'admin', 'super-admin'])) {
            $request->user ()->token ()->revoke ();
            return (object)[
                "status" => false,
                "message" => "User logout"
            ];
        }
        
        return (object) [
            'status' => true,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'data' => $user->toArray(),
            'message' => 'User logged in'
        ];
    }

    public function register($request): Object 
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
    
            if($request->type && $request->type == 'mobile') {
                $otp = $this->otpService->create (get_class($user), $user->email , '6', '30');
 
                if ($user->phone) {
                    $this->smsService->sendSms(
                                $user->phone, 
                                "Adashi: Your OTP is " . $otp->token, 
                                "ADASHI"
                            );
                } else {
                    $this->mailService->sendEmail(
                        $user->email, 
                        "Your OTP from Adashi", [
                            "content" => "Your OTP is ". $otp->token,
                            "greeting" => "Welcome"
                        ]
                    );
                }
            } else {
                $token = $user->createToken ('authToken')->plainTextToken;
                $mailStatus = $this->mailService->sendEmail(
                    $user->email, 
                    "Verify your account", [
                        "introLines" => [ "Kindly, click the link below to activate your account" ],
                        "content" =>   "Thanks, for using Adashi", 
                        "greeting" => "Hello",
                        "level" => "primary",
                        "actionUrl" => $callback_url . "?token=" .$token,
                        "actionText" => "Click to verify your account"
                    ]
                );
                Log::info('Mail status: '.$mailStatus);
            }
            DB::commit();
            return (object) [
                "status" => true,
                "data" => $user->toArray(),
                "message" => "Account was created successfully"
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object) [
                "status" => false,
                "data" => [],
                "message" => $e->getMessage ()
            ];
        }
    }

    public function update($conds, $request): Object {
        DB::beginTransaction();
        try {
            $user = $this->user->where($conds)
                                ->update($request);
            DB::commit();
            return (object) [
                "status" => true,
                "data" => $user->toArray(),
                "message" => "Account was updated successfully"
            ];
        } catch(\Exception $e) {
            DB::rollback();
            return (object) [
                "status" => false,
                "data" => [],
                "message" => $e->getMessage ()
            ];
        }
    }

    
    public function findOne($conds): Object {
       $user = $this->user->where($conds)->first();
       if(!$user) {
            return (object) [
                "status" => false,
                "message" => "No account found"
            ];
       }

       return (object) [
           "status" => true,
           "data" => $user->toArray(),
           "message" => "Account details"
       ];
    }

    public function verifyViaOTP($request): Object {
        $email = $request->email;
        $otp = $request->otp;
        $user = $this->user->where("email", $email)->first();
       
        if (!$user) { 
            return (object) [
                "status" => false,
                "message" => "User doesn't exit on this platform"
            ];
        }
        //Verify OTP
        $checkOtp = $this->otpService->validate(get_class($user), $user->email, $otp);
        if(!$checkOtp->status) { 
            return (object) [
                "status" => false,
                "message" => $checkOtp->message
            ];
        }

        $user->email_verified_at = Carbon::now()->timestamp;
        $user->save();

        return (object) [
            "status" => true,
            "data" => $user->toArray(),
            "message" => $checkOtp->message
        ];
    }

    public function resendCode($request): Object {

        $user = $this->user->where('email', $request->email)->first();

        if (!$user) { 
            return (object) [
                "status" => false,
                "message" => "User's doesn't exist on this platform"
            ];
        }

        if ($user->email_verified_at) { 
            return (object) [
                "status" => false,
                "message" => "Account aleady verified"
            ];
        }

        $user->email_verified_at = NULL;
        $user->save(); 

        if($request->type && $request->type == 'mobile') {
            $otp = $this->otpService->create (get_class($user), $user->email, '6', '30');
            //TODO: OTP email
            Log::info('Token for mobile');
            Log::info($otp->token);
            
            return (object) [
                "status" => true,
                "message" => "OTP sent"
            ];
        } 

        if(!$request->callback_url) {
            return (object) [
                "status" => false,
                "message" => "Add a callback URL"
            ];
        }
        
        $callback_url = preg_replace('{/$}', '', $request->callback_url);
        $token = $user->createToken ('authToken')->plainTextToken;
        Log::info("Registration Token");
        Log::info($token);

        return (object) [
            "status" => true,
            "message" => "Token sent"
        ];
        
    }

    public function requestPasswordResetToken($request): Object {
        return (object) [

        ];
    }
}