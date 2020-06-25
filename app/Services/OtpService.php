<?php

namespace App\Services;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OtpService {
    protected $otp;

    public function __construct(Otp $otp) {
        $this->otp = $otp;
    }

    public function create($identifier_type, string $identifier_id, int $digits = 4, int $validity = 10) {
        //Delete any previous OTP by that identifier
        $this->otp->where(['identifier_type' => $identifier_type, 'identifier_id' => $identifier_id, 'valid' => true])->delete();

        $token = str_pad ($this->generatePin (), 4, '0', STR_PAD_LEFT);

        if ($digits == 5)
            $token = str_pad($this->generatePin(5), 5, '0', STR_PAD_LEFT);

        if ($digits == 6)
            $token = str_pad($this->generatePin(6), 6, '0', STR_PAD_LEFT);

        $this->otp->create([
                        'identifier_type' => $identifier_type,
                        'identifier_id' => $identifier_id, 
                        'token' => $token,
                        'validity' => $validity
                    ]);
        return (object)[
            'status' => true,
            'token' => $token,
            'message' => 'OTP generated'
        ];
    }

    public function validate($identifier_type, string $identifier_id, string $token): Object {
        $checkOtp = $this->otp->where(['identifier_type' => $identifier_type, 'identifier_id' => $identifier_id, 'token' => $token])->first();

        if($checkOtp === null) {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
        if (!$checkOtp->valid) {
            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        }
        $carbon = new Carbon();
        $now = $carbon->now();
        $validity = $checkOtp->created_at->addMinutes($checkOtp->validity);

        if (strtotime($validity) < strtotime($now)) {
            $checkOtp->valid = false;
            $checkOtp->save();

            return (object)[
                'status' => false,
                'message' => 'OTP Expired'
            ];
        } 

        $checkOtp->valid = false;
        $checkOtp->save();

        return (object)[
            'status' => true,
            'message' => 'OTP is valid'
        ];
    
    }

    public function generatePin($digits = 4) {
        $i = 0;
        $pin = "";

        while ($i < $digits) {
            $pin .= mt_rand(0, 9);
            $i++;
        }

        return $pin;
    }


}