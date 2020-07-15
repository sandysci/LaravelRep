<?php

namespace App\Services;

use App\Services\Mailer\TMNotifyService;
use App\Services\Message\EmailMessage;

class MailService
{
    /**
     * @var TMNotifyService
     */
    protected $tMNotifyService;

    public function __construct(TMNotifyService $tMNotifyService)
    {
        $this->tMNotifyService = $tMNotifyService;
    }

    public function sendEmail(
        string $to,
        string $subject,
        $content,
        ?string $cc = null,
        ?string $from = null,
        ?string $bcc = null
    ) {
        $emailMessage = new EmailMessage($to, $subject, $content, $cc, $from, $bcc);

        return $this->tMNotifyService->sendEmail($emailMessage);
    }
}
