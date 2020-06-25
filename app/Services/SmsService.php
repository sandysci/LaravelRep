<?php

namespace App\Services;

use App\Services\Message\SmsMessage;
use App\Services\SmsMailer\TMNotifySMSService;

class SmsService
{
    private $tMNotifySMSService;

    public function __construct(TMNotifySMSService $tMNotifySMSService)
    {
        $this->tMNotifySMSService = $tMNotifySMSService;
    }

    public function sendSms(string $to, string $content, ?string $from = null)
    {
        return $this->tMNotifySMSService->sendSms(new SmsMessage($to, $from ?? 'Adashi', $content));
    }
}
