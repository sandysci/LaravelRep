<?php

namespace App\Services\Mailer;

use App\Services\Message\EmailMessage;

interface EmailProviderInterface
{
    public function sendEmail(
        EmailMessage $message
    );
}
