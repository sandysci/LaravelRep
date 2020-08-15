<?php

namespace App\Services\SmsMailer;

use App\Services\Message\SmsMessage;

interface SmsProviderInterface
{
    public function sendSms(
        SmsMessage $message
    ): bool;
}
