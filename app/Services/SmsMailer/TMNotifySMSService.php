<?php

namespace App\Services\SmsMailer;

use App\Services\Message\SmsMessage;
use Illuminate\Support\Facades\Http;

class TMNotifySMSService implements SmsProviderInterface
{
    //TODO: Convert to library
    private $clientId;
    private $tmNotifyClient;
    private $from;

    public function __construct()
    {
        //EN
        $this->tmNotifyClient = config('constants.notification.tmnotify.url.sms');
        $this->from = config('constants.notification.tmnotify.sms.from');
        $this->clientId = config('constants.notification.tmnotify.client_id');
        $this->cliendSecret = config('constants.notification.tmnotify.secret_key');
    }

    public function sendSms(SmsMessage $message): bool
    {
        $from = $this->from ?? $message->from();

        try {
            $params = [
                'send' => $from,
                'recipients' => $message->to(),
                'message' => $message->body(),
            ];
            $headers = [
                'client-id' => $this->clientId
            ];

            $response = Http::withHeaders($headers)->post($this->tmNotifyClient, $params);

            if ($response->failed()) {
                throw new \Exception('Error sending sms');
            }
            return true;
        } catch (\Throwable $exception) {
            //TODO: Log exceptions -GrayLog
            return false;
        }
    }
}
