<?php

namespace App\Services;

use App\Domain\Dto\Request\Integrations\Paystack\PaystackPaymentRequestDto;
use App\Domain\Dto\Value\Card\ChargeCardResponseDto;
use App\Domain\Dto\Value\PaymentProviderResponseDto;
use App\Models\Card;
use App\Models\User;
use App\Services\Payment\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CardService
{
    /**
     * @var PaystackService
     */
    private $paystackService;


    private $card;

    public function __construct(
        PaystackService $paystackService,
        Card $card
    ) {
        $this->paystackService = $paystackService;
        $this->card = $card;
    }

    public function pay($payload, ?string $channel = 'paystack'): PaymentProviderResponseDto
    {
        if ($channel && $channel === 'paystack') {
            //Format payload
            $paystackRequestDto = new PaystackPaymentRequestDto(
                $payload["authorization_code"],
                $payload["reference"],
                $payload["amount"],
                $payload["email"]
            );
            return $this->paystackService->makePayment($paystackRequestDto);
        }
    }

    public function verify(Request $request, ?string $channel = 'paystack'): PaymentProviderResponseDto
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
        //Format Payload
        // $formattedPayload = $this->paystackEventHandler->formatPayload($data);

        // return $this->paystackEventHandler->eventHandler($formattedPayload->event, $formattedPayload->payload);
        return null;
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

    public function getCard(string $id): ?Card
    {
        return $this->card->find($id);
    }

    public function getUserCards(User $user): Collection
    {
        return $this->card->where('user_id', $user->id)->get();
    }

    public function chargeCard(string $id, array $payload, string $channel = 'paystack'): PaymentProviderResponseDto
    {
        $card = $this->getCard($id);
        if (!$card->gw_authorization_code) {
            return new ChargeCardResponseDto(false, [], "No authorization token");
        }
        $paystackRequestDto = new PaystackPaymentRequestDto(
            $card->gw_authorization_code,
            $payload["reference"],
            $payload["amount"] * 100,
            $payload["email"]
        );

        if ($channel && $channel === 'paystack') {
            return $this->paystackService->makePayment($paystackRequestDto);
        }
        return $this->paystackService->makePayment($paystackRequestDto);
    }
}
