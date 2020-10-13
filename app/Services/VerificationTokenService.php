<?php

namespace App\Services;

use App\Helpers\RandomNumber;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Support\Carbon;

class VerificationTokenService
{
    public function create(User $user): string
    {
        $token = RandomNumber::generateVerificationToken();
        $expiredAt = Carbon::now()->addMinutes(VerificationToken::EXPIRED_AT);

        $verificationToken =  VerificationToken::updateOrCreate([
            'user_id' => $user->id
        ], [
            'token' => $token,
            'expires_at' => $expiredAt
        ]);

        return $verificationToken->token;
    }

    public function delete(User $user)
    {
        try {
            return VerificationToken::where('user_id', $user->id)->delete();
        } catch (\Exception $e) {
            return null;
        }
    }
}
