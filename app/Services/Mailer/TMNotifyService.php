<?php

namespace App\Services\Mailer;

use App\Services\Message\EmailMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TMNotifyService implements EmailProviderInterface
{
    private $clientId;
    private $clientSecret;
    private $tmNotifyClient;
    private $from;

    public function __construct()
    {
        $this->tmNotifyClient = config('constants.notification.tmnotify.url.email');
        $this->from = config('constants.notification.tmnotify.mail.from');
        $this->clientId = config('constants.notification.tmnotify.client_id');
        $this->cliendSecret = config('constants.notification.tmnotify.secret_key');
    }
    public function messageFormat(array $payload): ?array
    {
        $arr = [
            "content" => $payload['content'] ?? "",
            "body" => [
                "content" => $payload['content'] ?? "",
                "greeting" => $payload['greeting'] ?? "Greetings,",
                "introLines" => $payload['introLines'] ?? [],
                "outroLines" => $payload['outroLines'] ?? []
            ],
            "attachments" => $payload['attachments'] ?? ""
        ];

        if (!isset($payload['actionUrl'])) {
            return $arr;
        }

        $withButton = [
            "button" => [
                "level" => $payload["level"] ?? "primary", //Can be primary, success or error
                "actionUrl" => $payload['actionUrl'] ?? env('APP_URL'),
                "actionText" => $payload['actionText'] ?? "Click here"
            ]
        ];
        
        return array_merge($arr, $withButton);
    }

    public function sendEmail(EmailMessage $message): bool
    {
        $from = $this->from ?? $message->from();

        try {
            $params = [
                "provider" => "",
                "from" => $from,
                "subject" =>  $message->subject(),
                "recipients" => $message->to(),
                "header" => [
                    "title" => "The Email Header",
                    "bgColor" => "",
                    "appName" => env('APP_NAME'),
                    "appURL" => env('APP_URL'),
                    "appLogo" => ""
                ]
            ];
            if (is_array($message->body())) {
                $arr = $this->messageFormat($message->body());
                $params = array_merge($params, $arr);
            } else {
                $params['body'] = $message->body();
            }

            $headers = [
                'client-id' => $this->clientId
            ];

            $response = Http::withHeaders($headers)->post($this->tmNotifyClient, $params);
            Log::alert('Mail sent');
            if ($response->failed()) {
                throw new \Exception('Error sending email');
            }
            return true;
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
            return false;
        }
    }
}
