<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use App\Services\Payment\PaystackEventHandler;
use App\Services\Payment\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CardService
{
    /**
     * @var PaystackService
     */
    private $paystackService;

    private $paystackEventHandler;

    private $card;

    public function __construct(
        PaystackService $paystackService,
        PaystackEventHandler $paystackEventHandler,
        Card $card
    ){
        $this->paystackService = $paystackService;
        $this->paystackEventHandler = $paystackEventHandler;
        $this->card = $card;
    }

    public function pay($payload, ?string $channel = 'paystack'): Object
    {
        //Format payload
        if ($channel && $channel === 'paystack') {
            return $this->paystackService->makePayment($payload);
        }
        return $this->paystackService->makePayment($payload);
    }

    public function verify(Request $request, ?string $channel = 'paystack'): Object
    {
          //Format payload
        $payload = $request->all();
          if ($channel && $channel === 'paystack') {
                $payload = [
                    "reference" => $request->reference
                ];
                return $this->paystackService->verifyPayment($payload);
            }
        return $this->paystackService->verifyPayment($payload);
    }

    public function eventHandler($data) 
    {
        $formattedPayload = $this->paystackEventHandler->formatPayload($data);

        return $this->paystackEventHandler->eventHandler($formattedPayload->event, $formattedPayload->payload);
    }

    public function getExistingSimilarCards(array $conditions): Collection
    {
        $cards = $this->card->where([
                'last4'            => $conditions['data']['authorization']['last4'],
                'gw_customer_code' => $conditions['data']['customer']['customer_code'],
                'bank'             => $conditions['data']['authorization']['bank'],
                'country_code'     => $conditions['data']['authorization']['country_code'],
                'exp_month'        => $conditions['data']['authorization']['exp_month'],
                'exp_year'         => $conditions['data']['authorization']['exp_year'],
                ])->get();
        return $cards;
    }

    public function store(User $user, string $reference, array $payload): Card
    {
        return $this->card->create([
            'user_id'               => $user->id,
            'reference'             => $reference,
            'channel'               => $payload['data']['authorization']['channel'] ?? null,
            'gw_customer_id'        => $payload['data']['customer']['id'] ?? null,
            'gw_authorization_code' => $payload['data']['authorization']['gw_authorization_code'] ?? null,
            'card_type'             => $payload['data']['authorization']['card_type'] ?? null,
            'brand'                 => $payload['data']['authorization']['brand'] ?? null,
            'last4'                 => $payload['data']['authorization']['last4'] ?? null,
            'gw_customer_code'      => $payload['data']['customer']['customer_code'] ?? null,
            'bank'                  => $payload['data']['authorization']['bank'] ?? null,
            'country_code'          => $payload['data']['authorization']['country_code'] ?? null, 
            'exp_month'             => $payload['data']['authorization']['exp_month'] ?? null,
            'exp_year'              => $payload['data']['authorization']['exp_year'] ?? null,
            'description'           => $payload['data']['authorization']['description'] ?? null,
            'reusable'              => $payload['data']['authorization']['reusable'] ?? null,
            'signature'             => $payload['data']['authorization']['signature'] ?? null,
            'bank_number'           => $payload['data']['authorization']['bank_number'] ?? null
        ]);
    }
}

