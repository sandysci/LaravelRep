<?php

namespace App\Domain\Dto\Request\Integrations\Paystack;

use App\Domain\Dto\Request\PaymentProviderRequestDto;

class PaystackPaymentRequestDto extends PaymentProviderRequestDto
{
    public string $authorizationCode;
    public string $reference;

    public function __construct(
        string $authorizationCode,
        string $reference,
        float $amount,
        string $email
    ) {
        $this->authorizationCode = $authorizationCode;
        $this->reference = $reference;
        $this->email = $email;
        $this->amount = $amount;
    }
}
