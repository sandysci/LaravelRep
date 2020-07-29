<?php

namespace App\Domain\Dto\Value\SavingCycleBilling;

use App\Domain\Dto\Value\PaymentProviderResponseDto;

class SavingCycleBillingResponseDto extends PaymentProviderResponseDto
{
    public bool $status;
    public array $data;
    public string $message;

    public function __construct(bool $status, array $data, string $message)
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }
}
