<?php

namespace App\Domain\Dto\Value\UserProfile;

class BvnVerificationResponseDto
{
    public bool $status;
    public string $message;

    public function __construct(bool $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }
}
