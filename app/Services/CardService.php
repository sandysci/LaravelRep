<?php

namespace App\Services;

use App;
use App\Domain\Dto\Request\Card\CreateDto;
use App\Domain\Dto\Request\Integrations\Paystack\PaystackPaymentRequestDto;
use App\Domain\Dto\Value\Card\ChargeCardResponseDto;
use App\Domain\Dto\Value\Card\CreateCardDto;
use App\Domain\Dto\Value\PaymentProviderDto;
use App\Domain\Dto\Value\PaymentProviderResponseDto;
use App\Helpers\RandomNumber;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Payment\PaystackService;
use Illuminate\Support\Collection;

class CardService
{
    private PaystackService $paystackService;
    protected $transactionService;
    protected $walletService;


    private $card;

    public function __construct(
        PaystackService $paystackService,
        TransactionService $transactionService,
        WalletService $walletService
    ) {
        $this->paystackService = $paystackService;
        $this->transactionService = $transactionService;
        $this->walletService = $walletService;
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
        if ($channel !== 'paystack') {
            return new PaymentProviderDto(false, [], "Wrong payment channel");
        }
        //Format payload
        $paystackRequestDto = new PaystackPaymentRequestDto(
            $payload["authorization_code"],
            $payload["reference"],
            $payload["amount"],
            $payload["email"]
        );
        return $this->paystackService->makePayment($paystackRequestDto);
    }

    public function verify(string $reference, ?string $channel = 'paystack'): PaymentProviderResponseDto
    {

        if ($channel !== 'paystack') {
            return new PaymentProviderDto(false, [], "Wrong payment channel");
        }
        //Format payload
        $payload = [
            "reference" => $reference
        ];
        return $this->paystackService->verifyPayment($payload);
    }


    public function store(User $user, string $reference, array $payload): Card
    {
        return Card::create([
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

    public function storeCard(CreateDto $request, User $user): CreateCardDto
    {
        $reference = $request->reference;
        $conds = [
            'reference' => $reference,
            'user_id' => $user->id,
            'type' => 'debit'
        ];
        $transaction = $this->transactionService->findWhere($conds);

        if (!$transaction) {
            return new CreateCardDto(false, 'This transaction doesn\'t exist in our system');
        }

        if ($transaction->status == 'success') {
            return new CreateCardDto(false, 'This transaction has been recorded in our system');
        }

        $response = $this->verify($request->reference, $request->channel);

        if (!$response->status) {
            $transaction->status = 'failed';
            $transaction->attempt += 1;
            return new CreateCardDto(false, $response->message);
        }

        if ($response->data['data']['customer']['email'] !== $user->email) {
            return new CreateCardDto(false, "Invalid request");
        }

        // FundWallet
        $this->walletService->incrementBalance($user, $transaction->amount);

        $transaction->status = $response->data['data']['status'] ?? $transaction->status;
        $transaction->attempt += 1;

        //Run card validation
        $this->addCardValidation($transaction, $response);

        $paymentAuth = $this->store($user, $reference, $response->data);

        $transaction->payment_gateway_type = get_class($paymentAuth);
        $transaction->payment_gateway_id = $paymentAuth->id;
        $transaction->description = 'New Card added';
        $transaction->save();

        return new CreateCardDto(true, "Card was created successfully");
    }

    public function getCard(string $id): ?Card
    {
        return Card::find($id);
    }

    public function getUserCards(User $user): Collection
    {
        return Card::where('user_id', $user->id)->get();
    }

    public function getUserCard(User $user, string $id): ?Card
    {
        return Card::where(['user_id' => $user->id, 'id' => $id])->with('user', 'user.userProfle')->first();
    }

    public function chargeCard(string $id, array $payload, string $channel = 'paystack'): PaymentProviderResponseDto
    {
        if ($channel !== 'paystack') {
            return new PaymentProviderDto(false, [], "Wrong payment channel");
        }

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

        return $this->paystackService->makePayment($paystackRequestDto);
    }

    protected function addCardValidation(Transaction $transaction, $response): ?CreateCardDto
    {
        if (!$response->data['data']['authorization']) {
            $transaction->save();
            return new CreateCardDto(false, "Unable to get an authorization code for this card");
        }

        if (!$response->data['data']['authorization']['reusable']) {
            $transaction->save();
            return new CreateCardDto(false, "Card is not reusable");
        }

        $checkCard = $this->getExistingSimilarCards($response->data);

        if (count($checkCard) > 0 && App::environment('production')) {
            $transaction->payment_gateway_type = get_class($checkCard->first());
            $transaction->payment_gateway_id = $checkCard->first()->id;
            $transaction->description = 'Existing Card Used for the transaction';
            $transaction->save();

            return new CreateCardDto(false, "Card is already in the system");
        }
        return null;
    }

    protected function getExistingSimilarCards(array $conditions): Collection
    {
        return Card::where([
            'last4'            => $conditions['data']['authorization']['last4'],
            'gw_customer_code' => $conditions['data']['customer']['customer_code'],
            'bank'             => $conditions['data']['authorization']['bank'],
            'country_code'     => $conditions['data']['authorization']['country_code'],
            'exp_month'        => $conditions['data']['authorization']['exp_month'],
            'exp_year'         => $conditions['data']['authorization']['exp_year'],
        ])->get();
    }
}
