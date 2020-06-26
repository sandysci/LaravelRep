<?php

namespace App\Services\Payment;

use App\Events\SuccessfulPaystackPaymentOccured;

class PaystackEventHandler implements PaystackEventType
{
    public function eventHandler(string $event, array $payload)
    {
        if($event === PaystackEventType::CHARGE_SUCCESS)
        {
            return $this->chargeSuccess($payload);
        }
        return ;
    }

    public function formatPayload($payload)
    {
       return (object) [
            'event'  => $payload->event,
            'payload' => $payload->data
       ];
    }

    public function chargeSuccess($payload) 
    {
        event(new SuccessfulPaystackPaymentOccured($payload));
    }

    public function transferSuccess()
    {

    }

    public function transferFailed()
    {

    }

    public function paymentRequestPending()
    {

    }

    public function paymentRequestSuccess()
    {

    }
}
