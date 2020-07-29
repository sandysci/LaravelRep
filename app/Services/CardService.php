<?php

namespace App\Services;

use App;
use App\Domain\Dto\Request\Integrations\Paystack\PaystackPaymentRequestDto;
use App\Domain\Dto\Value\Card\ChargeCardResponseDto;
use App\Domain\Dto\Value\Card\CreateCardDto;
use App\Domain\Dto\Value\PaymentProviderResponseDto;
use App\Helpers\RandomNumber;
use App\Models\Card;
use App\Models\User;
use App\Services\Payment\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CardService
{
    /**
     * @var PaystackService
     */
    private $paystackService;

    protected $transactionService;

    protected $walletService;


    private $card;

    public function __construct(
        PaystackService $paystackService,
        TransactionService $transactionService,
        WalletService $walletService,
        Card $card
    ) {
        $this->paystackService = $paystackService;
        $this->transactionService = $transactionService;
        $this->walletService = $walletService;
        $this->card = $card;
    }

    public function initializeCardTrans($request): CreateCardDto
    {
        $reference = 'CV-' . RandomNumber::generateTransactionRef();
        $amount = config('constants.default_card_amount');

        $request->reference = $reference;
        $request->description = "Initializing Add Card Transaction";
        $request->amount = $amount;
        $request->type = 'debit';
        $request->status = 'processing';
        //Store Transaction
        $transaction = $this->transactionService->store($request, $request->user(), $request->user());

        return new CreateCardDto(true, "Card transaction initialize", $transaction->toArray());
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
            'gw_authorization_code' => $payload['data']['authorization']['authorization_code'] ?? null,
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

    public function storeCard($request): CreateCardDto
    {
        $reference = $request->reference;
        $conds = [
            'reference' => $reference,
            'user_id' => $request->user()->id,
            'type' => 'debit'
        ];
        $tx = $this->transactionService->findWhere($conds);

        if (!$tx) {
            return new CreateCardDto(false, 'This transaction doesn\'t exist in our system');
        }

        if ($tx->status == 'success') {
            return new CreateCardDto(false, 'This transaction has been recorded in our system');
        }


        $response = $this->verify($request, $request->channel);
        Log::info("Check Status of Reference Code");
        

        if (!$response->status) {
            $tx->status = 'failed';
            $tx->attempt += 1;

            return new CreateCardDto(false, $response->message);
        }

        if ($response->data['data']['customer']['email'] !== $request->user()->email) {
            return new CreateCardDto(false, "Invalid request");
        }

        // FundWallet
        $this->walletService->incrementBalance($request->user(), $tx->amount);

        $tx->status = $response->data['data']['status'] ?? $tx->status;
        $tx->attempt += 1;

        //Check if similar card exist
        if (!$response->data['data']['authorization']) {
            $tx->save();
            return new CreateCardDto(false, "Unable to get an authorization code for this card");
        }

        if (!$response->data['data']['authorization']['reusable']) {
            $tx->save();
            return new CreateCardDto(false, "Card is not reusable");
        }

        $checkCard = $this->getExistingSimilarCards($response->data);

        if (count($checkCard) > 0 && App::environment('production')) {
            $tx->payment_gateway_type = get_class($checkCard->first());
            $tx->payment_gateway_id = $checkCard->first()->id;
            $tx->description = 'Existing Card Used for the transaction';
            $tx->save();

            return new CreateCardDto(false, "Card already in the system");
        }


        $paymentAuth = $this->store($request->user(), $reference, $response->data);

        $tx->payment_gateway_type = get_class($paymentAuth);
        $tx->payment_gateway_id = $paymentAuth->id;
        $tx->description = 'New Card added';
        $tx->save();

        return new CreateCardDto(true, "Card was created successfully");
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
