<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService implements CardInterface 
{

    protected $paystackSecretKey;
    protected $paystackPublicKey;

    public function __construct() {
        $this->paystackSecretKey = config('constants.payment_gateway.paystack.secret_key');
        $this->paystackPublicKey = config('constants.payment_gateway.paystack.public_key');
    }
    public function initializeTrans() 
    {

    }

    public function makePayment(array $payload): Object {
        try {
            $url = 'https://api.paystack.co/transaction/charge_authorization';

            $data = [
                'authorization_code' => $payload['authorization_code'],
                'email' => $payload['email'],
                'amount' =>$payload['amount'] * 100,
                'reference' => $payload['reference']
            ];
            $response = Http::withToken($this->paystackSecretKey)->post($url, $data);
    
            if($response->failed()) { 
                return (object) [
                    "status" => false,
                    "data" => [],
                    "message" => "There was an error with this transaction"
                ];
            }
    
            return (object) [
                'status' => true,
                'data' => $response->json(),
                'message' => "Transaction was successful"
            ];
        } catch(\Exception $e) {
            return (object) [
                'status' => false,
                'data' => [],
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment(array $payload): Object
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
        $url = 'https://api.paystack.co/transaction/verify/'.$reference;

        $response = Http::withToken($this->paystackSecretKey)->get($url);

        if ($response->failed()) { 
            return (object) [
                'status' => false,
                'data' => $response->json(),
                'message' => $response->json()['message'] ?? "Failed Transaction"
            ];
        }

        $data = $response->json()['data'];
        if ($data) 
        {
              //Check for insufficient fund, and other related issues
            if($data['status'] !== "success")
            {
                return (object) [
                    'status' => false,
                    'data' => $response->json(),
                    'message' => $data['gateway_response'] ?? "The transaction was not successful"
                ];
            } else {
                return (object) [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => "Successful request"
                ];
            }
            
        }
     
        return (object) [
            'status' => false,
            'data' => $response->json(),
            'message' => $response->json()['message'] ?? "Failed Transaction"
        ];
    }

    public function transferToBank($data) 
    {

        $url = 'https://api.paystack.co/transfer';
    
        $response = Http::withToken($this->paystackSecretKey)->post($url, $data);

        if($response->failed()) {
            return (object) [
                "status" => false,
                "data" => $response->json(),
                "message" => "There was an error when trying to transfer money to user's account"
            ];
        }
        return (object) [
            'status' => true,
            'data' =>  $response->json(),
            'message' => "Money was successfully sent to user's account",
        ];
    }

    public function resolveBankDetails ($account, $bank)
    {
     
        $url  = 'https://api.paystack.co/bank/resolve';
        $params = [
            'account_number' => $account,
            'bank_code' => $bank
        ];

        $response = Http::withToken($this->paystackSecretKey)->get($url, $params);
        if($response->failed()) {
            return (object) [
                "status" => false,
                "data" => $response->json(),
                "message" => "There was an error encountered, when trying to get bank's details"
            ];
        }

        return (object) [
            'status' => true,
            'data' =>  $response->json(),
            'message' => "User's bank details",
        ];
    }

    public function createTransferRecipient($payload) 
    {
        $url = "https://api.paystack.co/transferrecipient";

        $response = Http::withToken($this->paystackSecretKey)->post($url, $payload);
        if ($response->failed()) {
            return (object) [
                'status' => false,
                'data' => $response->json(),
                'message' => 'Error creating transfer recipient'
            ];
        }
        return (object) [
            'status' => true,
            'data' => $response->json(),
            'message' => 'Transfer recipient created'
        ];
    }

}