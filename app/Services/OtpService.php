<?php

namespace App\Services;

use App\Domain\Dto\Value\Otp\OtpResponseDto;
use App\Models\Otp;
use Carbon\Carbon;

class OtpService
{
    protected Otp $otp;

    public function __construct(Otp $otp)
    {
        $this->otp = $otp;
    }

    public function create($identifier_type, string $identifier_id, int $digits = 4, int $validity = 10): OtpResponseDto
    {
        //Delete any previous OTP by that identifier
        $this->otp->where(['identifier_type' => $identifier_type, 'identifier_id' => $identifier_id, 'valid' => true])->delete();

        $token = str_pad($this->generatePin(), 4, '0', STR_PAD_LEFT);

        if ($digits == 5) {
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);
        }

        if ($digits == 6) {
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);
        }
        $this->otp->create([
            'identifier_type' => $identifier_type,
            'identifier_id' => $identifier_id,
            'token' => $token,
            'validity' => $validity
        ]);
        return new OtpResponseDto(true, 'OTP generated', [], $token);
    }

    public function validate($identifier_type, string $identifier_id, string $token): OtpResponseDto
    {
        $checkOtp = $this->otp->where(['identifier_type' => $identifier_type, 'identifier_id' => $identifier_id, 'token' => $token])->first();

        if ($checkOtp === null) {
            return new OtpResponseDto(false, 'OTP does not exist');
        }
        if (!$checkOtp->valid) {
            return new OtpResponseDto(false, 'OTP is not valid');
        }

        $carbon = new Carbon();
        $now = $carbon->now();
        $validity = $checkOtp->created_at->addMinutes($checkOtp->validity);

        if (strtotime($validity) < strtotime($now)) {
            $checkOtp->valid = false;
            $checkOtp->save();

            return new OtpResponseDto(false, 'OTP has Expired');
        }

        $checkOtp->valid = false;
        $checkOtp->save();

        return new OtpResponseDto(true, 'OTP is valid');
    }

    public function generatePin($digits = 4)
    {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }
}
