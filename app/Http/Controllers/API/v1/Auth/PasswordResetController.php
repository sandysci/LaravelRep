<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
class PasswordResetController extends Controller
{

    /**
     * Reset password
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function reset(PasswordResetRequest $request): JsonResponse {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->responseError([], 'We can\'t find a user with that e-mail address.');
        }

        if($request->type  && $request->type == 'mobile') {
            $response = $this->resetViaOtp ($request, $user);
        } else {
            $response = $this->resetViaToken ($request);
        }
        
        if(!$response->status) {
            return $this->responseError([], $response->message, 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken ('authToken')->plainTextToken;

        //TODO: send email for successful password reset
        Log::info('Password reset successfully');
        $options = [
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
        return $this->responseSuccess($user->toArray(), 'Password reset successfully', $options);
    }

    public function resetViaToken(Request $request): Object {
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
    public function resetViaOtp(Request $request, User $user): Object {
        $validateOtp = $this->otpService->validate (get_class($user), $user->email, $request->token);

        if ( !$validateOtp->status ) {
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
