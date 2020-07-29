<?php

namespace App\Domain\Dto\Value\Otp;

/**
 * OtpResponseDto - Data transfer object for user service
 */
class OtpResponseDto
{
    public bool $status;
    public string $message;
    public ?array $data;
    public ?string $token;
    /**
     * @param boolean $status
     * @param string $message
     * @param array|null $data
     * @param string|null $token
     */
    public function __construct(bool $status, string $message, ?array $data = [], ?string $token = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->token = $token;
    }
}
