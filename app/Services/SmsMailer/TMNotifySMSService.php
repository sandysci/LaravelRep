<?php

namespace App\Services\SmsMailer;

use App\Services\Message\SmsMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TMNotifySMSService implements SmsProviderInterface
{
    //TODO: Convert to library
    private $clientId;
    private $clientSecret;
    private $tmNotifyClient;
    private $from;

    public function __construct()
    {
       //EN
       $this->tmNotifyClient = "https://services-staging.tm30.net/alerts/v1/sms";
       $this->from = config('constants.notification.tmnotify.sms.from');
       $this->clientId = config('constants.notification.tmnotify.client_id');
       $this->cliendSecret = config('constants.notification.tmnotify.secret_key');

    }

    public function sendSms(SmsMessage $message)
    {
        $from = $this->from ?? $message->from();

        try {
            $params = [
                'provider' => 'multitexter',
                'send' => $from,
                'recipients' => $message->to(),
                'message' => $message->body(),
            ];
            $headers = [
                'client-id' => $this->clientId
            ];

            $response = Http::withHeaders($headers)->post($this->tmNotifyClient, $params);
            
            if($response->failed()) {
                throw new \Throwable('Error sending sms');
            }
            return null;
        } catch (\Throwable $exception) {

            return null;
        }
    }
}
