<?php

namespace App\Services;

use App\Services\Payment\PaystackService;

class CardService
{
    /**
     * @var PaystackService
     */
    private $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function pay($payload, ?string $channel = 'paystack') {
        //Format payload
        if ($channel && $channel === 'paystack') {
            return $this->paystackService->makePayment($payload);
        }
        return $this->paystackService->makePayment($payload);
    }

    public function verify($payload, ?string $channel = 'paystack') {
          //Format payload
          if ($channel && $channel === 'paystack') {
            return $this->paystackService->verifyPayment($payload);
        }
        return $this->paystackService->verifyPayment($payload);
    }
}
