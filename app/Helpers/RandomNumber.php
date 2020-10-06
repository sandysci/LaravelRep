<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class RandomNumber
{

    protected static function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        } // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::cryptoRandSecure(0, $max - 1)];
        }

        return $token;
    }

    // Generate Standard Payment Transaction ID
    public static function generateTransactionRef()
    {
        //generate most suitable transaction ref
        $ref = date("Ymd") . time() . mt_rand(10000, 99999);
        return $ref;
    }

     /**
     * Generate the verification token.
     *
     * @return string|bool
     */
    public static function generateVerificationToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }
}
