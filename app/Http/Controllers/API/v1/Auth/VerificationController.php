<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class VerificationController extends Controller
{
    protected $userService;
    protected $otpService;

    public function __construct(UserService $userService, OtpService $otpService) {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    public function verify(Request $request) {
        try {
            $user = User::find( request()->user()->id);
            if(!$user) { throw new \Exception("This activation token is invalid or has expired."); }

            if($user->email_verified_at) { 
                return $this->responseSuccess([], "Account already verified");
            }

            $user->email_verified_at = Carbon::now()->timestamp;
            $user->save();

            return $this->responseSuccess($user->toArray(), "Your email has been verified");
        } catch(\Exception $e) {
            return $this->responseException($e, 400, $e->getMessage());
        }
    }
    public function verifyOTP(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'otp' => 'required'
            ]);
            
            if ($validator->fails()) { return $this->responseValidationError($validator);}
    
            $user = $this->userService->verifyViaOTP($request);

            if(!$user->status) { return $this->responseError([], $user->message); }

            //TODO: send email 
            Log::info("Account verification alert");
            Log::info($user->message);

            return $this->responseSuccess($user->data, $user->message);
        } catch(\Exception $e) {
            return $this->responseException($e, 400, $e->getMessage());   
        }
    }

    public function resendVerificationCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'callback_url' => 'sometimes'
        ]);
        if ($validator->fails()) { return $this->responseValidationError($validator);}
        $user = $this->userService->resendCode($request);
        if(! $user->status) {
            return $this->responseError([], $user->message, 400);
        }

        return $this->responseSuccess([], $user->message);

    }
}
