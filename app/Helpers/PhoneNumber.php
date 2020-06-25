<?php
namespace App\Helpers;

class PhoneNumber {
    public static function formatToNGR(string $phoneNumber): String {
        $checkIfPlusSign = substr($phoneNumber, 0, strlen("+"));
        if($checkIfPlusSign  === "+") {
            return $phoneNumber;  
        } 
        $lastTenDigits = substr($phoneNumber, -10);
        $newPhoneNumber = preg_replace('/^(?:\+?234|0)?/','+234', $lastTenDigits);
        return $newPhoneNumber;
    }
}