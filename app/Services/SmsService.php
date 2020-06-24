<?php

namespace App\Services;

use App\Services\Mailer\TMNotifyService;
use App\Services\Message\SmsMessage;

class SmsService
{
    private $tMNotifyService;

    public function __construct(TMNotifyService $tMNotifyService)
    {
        $this->tMNotifyService = $tMNotifyService;
    }

    public function sendSms(string $to, string $content, ?string $from = null)
    {
        return $this->tMNotifyService->sendSms(new SmsMessage($to, $from ?? 'Adashi', $content));
    }
}
