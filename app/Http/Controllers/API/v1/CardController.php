<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Services\CardService;
use App\Helpers\RandomNumber;
use App\Http\Requests\PaystackWehookRequest;
use App\Http\Requests\StoreCardRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    protected $cardService;
    public function __construct(
        CardService $cardService,
        TransactionService $transactionService
    ){
        $this->cardService = $cardService;
        $this->transactionService = $transactionService;
    }
    public function initialize(Request $request) {
        $reference = 'CV-'. RandomNumber::generateTransactionRef();
        $amount = config('constants.default_card_amount');

        $request->reference = $reference;
        $request->description = "Initializing Add Card Transaction";
        $request->amount = $amount;
        $request->type = 'debit';
        $request->status = 'processing';
        //Store Transaction
        $transaction = $this->transactionService->store($request, $request->user(), $request->user());

        return $this->responseSuccess($transaction->data, 'Initializing Card Transaction');
    }

    public function store(StoreCardRequest $request)   
    {
        try {
            //TODO: Move codes to service layer
            $reference = $request->reference;
            $conds = [ 
                'reference' => $reference,
                'user_id' => $request->user()->id,
                'type' => 'debit'
            ];
            $tx = $this->transactionService->findWhere($conds);

            if (!$tx) {
                return $this->responseError([], 'This transaction doesn\'t exist in our system');
            }

            if ($tx->status == 'success') {
                return $this->responseError([], 'This transaction has been recorded in our system');
            }

        
            $response = $this->cardService->verify($request, $request->channel);
            Log::info("Check Status of Reference Code");
          
            if(!$response->status) {
                $tx->status = 'failed';
                $tx->attempt += 1;

                return $this->responseError([], $response->message);
            }
            // FundWallet
            $this->fundWallet();

            if($response->data['data']['customer']['email'] !== $request->user()->email) 
            {
                return $this->responseError([], "Invalid request");
            }

            $tx->status = $response->data['data']['status'] ?? $tx->status;
            $tx->amount = $response->data['data']['amount'] / 100;
            $tx->attempt += 1;

            //Check if similar card exist
            if(!$response->data['data']['authorization'])
            {
                $tx->save();
                return $this->responseError([], "Unable to get an authorization code for this card");
            }
               
            if(!$response->data['data']['authorization']['reusable'])
            {
                $tx->save();
                return $this->responseError([], "Card is not reusable");       
            } 

            $checkCard = $this->cardService->getExistingSimilarCards($response->data);

            if(count($checkCard) > 0)
            {
                $tx->payment_gateway_type = get_class($checkCard->first());
                $tx->payment_gateway_id = $checkCard->first()->id;
                $tx->description = 'Existing Card Used for the transaction';
                $tx->save();
                
                return $this->responseError([], "Card already in the system");
            } 
            
            
            $paymentAuth = $this->cardService->store($request->user(), $reference, $response->data);
            
            $tx->payment_gateway_type = get_class($paymentAuth);
            $tx->payment_gateway_id = $paymentAuth->id;
            $tx->description = 'New Card added';
            $tx->save();
            return $this->responseSuccess([], "Card saved successfully");
        
        } catch (\Exception $error) {
            return $this->responseError([], 'An error occurred Details: '. $error->getMessage());  
        }
    }

    public function handleWebhook(Request $request) 
    {
        if (!$request->has('channel'))
        {
           return $this->responseError([], 'Wrong channel'); 
        }
        
        $channel = $request->query('channel');
            if ($channel === "paystack")
            {
                return $this->paystackWebhookHandler($request);
            }
        
        return $this->responseError([], 'No available channel');
    }

    public function paystackWebhookHandler (PaystackWehookRequest $request)
    {
        $response = $this->cardService->eventHandler($request);
        return $this->responseSuccess([], "Successful request");
    }

    private function fundWallet()
    {
        //TODO:
    }
}
