<?php

namespace App\Domain\Dto\Request;

abstract class PaymentProviderRequestDto
{
    protected int $amount;
    protected string $email;
}
