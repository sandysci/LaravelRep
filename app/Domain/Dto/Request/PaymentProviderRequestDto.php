<?php

namespace App\Domain\Dto\Request;

abstract class PaymentProviderRequestDto
{
    public int $amount;
    public string $email;
}
