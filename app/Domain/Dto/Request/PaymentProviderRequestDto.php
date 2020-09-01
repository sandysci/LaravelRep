<?php

namespace App\Domain\Dto\Request;

abstract class PaymentProviderRequestDto
{
    public float $amount;
    public string $email;
}
