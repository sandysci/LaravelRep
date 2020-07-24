<?php

namespace App\Services\Payment;

use App\Domain\Dto\Request\PaymentProviderRequestDto;
use App\Domain\Dto\Value\PaymentProviderResponseDto;

interface CardInterface
{
    public function makePayment(PaymentProviderRequestDto $payload): PaymentProviderResponseDto;
    public function verifyPayment(array $payload): PaymentProviderResponseDto;
}
