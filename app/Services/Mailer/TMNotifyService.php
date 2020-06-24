<?php

namespace App\Services\Mailer;

use App\Services\Message\EmailMessage;
use Http;
use Illuminate\Support\Facades\Log;

class TMNotifyService implements EmailProviderInterface
{
    private $clientId;
    private $clientSecret;
    private $tmNotifyClient;
    private $from;

    public function __construct()
    {
       //EN
       $this->tmNotifyClient = "";
       $this->from = env('');
    }

    public function sendEmail(EmailMessage $message)
    {
        $from = $this->from ?? $message->from();

        try {
            $params = [
                'from' => $from,
                'to' => $message->to(),
                'subject' => $message->subject(),
                'html' => $message->body(),
            ];
            $headers = [

            ];
            $response = Http::withHeaders($headers)->post($this->tmNotifyClient, $params);
            
            if($response->failed) {
                throw new \Throwable('Error sending email');
            }
            return null;
        } catch (\Throwable $exception) {

            return null;
        }
    }
}
