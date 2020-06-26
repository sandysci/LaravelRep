<?php

namespace App\Services;

use App\Services\Payment\PaystackEventHandler;
use App\Services\Payment\PaystackService;

class CardService
{
    /**
     * @var PaystackService
     */
    private $paystackService;

    private $paystackEventHandler;

    public function __construct(
        PaystackService $paystackService,
        PaystackEventHandler $paystackEventHandler
    ){
        $this->paystackService = $paystackService;
        $this->paystackEventHandler = $paystackEventHandler;
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

    public function eventHandler($data) 
    {
        $formattedPayload = $this->paystackEventHandler->formatPayload($data);

        return $this->paystackEventHandler->eventHandler($formattedPayload->event, $formattedPayload->payload);
    }
}
