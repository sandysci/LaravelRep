<?php

namespace App\Domain\Dto\Value\Integrations\Paystack;

use App\Domain\Dto\Value\PaymentProviderResponseDto;

class PaystackResponseDto extends PaymentProviderResponseDto
{
    public array $data;
    public string $message;

    public function __construct(bool $status, array $data, string $message)
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }
}
