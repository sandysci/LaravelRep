<?php

namespace App\Services\Payment;

use App\Domain\Dto\Request\PaymentProviderRequestDto;
use App\Domain\Dto\Value\Integrations\Paystack\PaystackResponseDto;
use App\Domain\Dto\Value\PaymentProviderResponseDto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService implements CardInterface
{

    protected $paystackSecretKey;
    protected $paystackPublicKey;

    public function __construct()
    {
        $this->paystackSecretKey = config('constants.payment_gateway.paystack.secret_key');
        $this->paystackPublicKey = config('constants.payment_gateway.paystack.public_key');
    }
    public function initializeTrans()
    {
    }

    public function makePayment(PaymentProviderRequestDto $payload): PaymentProviderResponseDto
    {
        try {
            $url = 'https://api.paystack.co/transaction/charge_authorization';

            $data = [
                'authorization_code' => $payload->authorizationCode,
                'email' => $payload->email,
                'amount' => $payload->amount * 100,
                'reference' => $payload->reference
            ];
            $response = Http::withToken($this->paystackSecretKey)->post($url, $data);

            if ($response->failed()) {
                return new PaystackResponseDto(false, [], "There was an error with this transaction");
            }

            return new PaystackResponseDto(true, $response->json(), "Transaction was successful");
        } catch (\Exception $e) {
            return new PaystackResponseDto(false, [], $e->getMessage());
        }
    }

    public function verifyPayment(array $payload): PaymentProviderResponseDto
    {
        $reference = $payload['reference'];
        Log::info("Reference Code: " . $reference);
        if (!$reference) {
            return (object) [
                "status" => false,
                "data" => [],
                "message" => "No reference found"
            ];
        }
        $url = 'https://api.paystack.co/transaction/verify/' . $reference;

        $response = Http::withToken($this->paystackSecretKey)->get($url);

        if ($response->failed()) {
            return new PaystackResponseDto(false, $response->json(), $response->json()['message'] ?? "Failed Transaction");
        }

        $data = $response->json()['data'];
        if ($data) {
            //Check for insufficient fund, and other related issues
            if ($data['status'] !== "success") {
                return new PaystackResponseDto(false, $response->json(), $data['gateway_response'] ?? "The transaction was not successful");
            } else {
                return new PaystackResponseDto(true, $response->json(), "Successful request");
            }
        }

        return new PaystackResponseDto(false, $response->json(), $response->json()['message'] ?? "Failed Transaction");
    }

    public function transferToBank($data): PaymentProviderResponseDto
    {

        $url = 'https://api.paystack.co/transfer';

        $response = Http::withToken($this->paystackSecretKey)->post($url, $data);

        if ($response->failed()) {
            return new PaystackResponseDto(false, $response->json(), "There was an error when trying to transfer money to user's account");
        }
        return new PaystackResponseDto(true, $response->json(), "Money was successfully sent to user's account");
    }

    public function resolveBankDetails($account, $bank): PaymentProviderResponseDto
    {

        $url  = 'https://api.paystack.co/bank/resolve';
        $params = [
            'account_number' => $account,
            'bank_code' => $bank
        ];

        $response = Http::withToken($this->paystackSecretKey)->get($url, $params);
        if ($response->failed()) {
            return new PaystackResponseDto(false, $response->json(), "There was an error encountered, when trying to get bank's details");
        }

        return new PaystackResponseDto(true, $response->json(), "User's bank details");
    }

    public function createTransferRecipient($payload): PaymentProviderResponseDto
    {
        $url = "https://api.paystack.co/transferrecipient";

        $response = Http::withToken($this->paystackSecretKey)->post($url, $payload);
        if ($response->failed()) {
            return new PaystackResponseDto(false, $response->json(), 'Error creating transfer recipient');
        }
        return new PaystackResponseDto(true, $response->json(), 'Transfer recipient created');
    }
}
