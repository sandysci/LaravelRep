<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use Str;

class ForgotPasswordController extends Controller
{
     /**
     * Create token password reset
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function create(Request $request) {
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
            $otp = $this->otpService->create ($user->email, '6', '30');
            Log::info($otp->token);
            //TODO: otp email
            return $this->responseSuccess([], 'We have send your password reset token!');
        }

        $callback_url = preg_replace('{/$}', '', $request->callback_url);

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email],[
                'email' => $user->email,
                'token' => Str::random(60)
             ]);
        if ($passwordReset) {
            //TODO: token email
            return $this->responseSuccess([], 'We have e-mailed your password reset link!');
        }
        return $this->responseError([], 'Unreacheable error!', 400);
    }
}
