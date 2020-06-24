<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\CardService;
use App\Utility\RandomNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class CardController extends Controller
{
    protected $cardService;
    public function __construct(
        CardService $cardService
    ){
        $this->cardService = $cardService;
    }
    public function initialize(Request $request) {
        $reference = 'CV-'. RandomNumber::generateTransactionRef();
        $amount = config('constants.default_card_amount');

        //Store Transaction

        return $this->responseSuccess([], 'Initializing Card Transaction');
    }

    public function store(Request $request)   {
        try {
            $validator = Validator::make($request->all(), [
                'reference' => 'required'
            ]);

            if ($validator->fails()) { return $this->responseValidationError($validator, "Reference Code is needed");}
            
            $reference = $request->reference;
            $tx = Transaction::where([
                'reference' => $reference,
                'user_id' => $request->user()->id,
                'type' => 'debit'
            ])->first();

            if (!$tx) {
                return $this->responseError([], 'This transaction doesn\'t exist in our system');
            }

            if ($tx->status == 'success') {
                return $this->responseError([], 'This transaction has been recorded in our system');
            }

        
            $response = $this->cardService->verify($reference);
            Log::info("Check Status of Reference Code");
            Log::info($response);
            if(!$response->status) {
                // if($response->data->status === 'success') {
                    //Check for insufficient fund, and 
                // }
                return $this->responseError([], $response->message);
            }
            //Check if similar card exist
            $checkCard = $this->cardService->find();
            //Store Card info
            if(!$checkCard->status) {
                return $this->responseError([], $checkCard->message);
            }
            
            return $this->responseSuccess([], $response->message);
             // Request for Card details from Paystack
            if ($res["status"] == "success" ) {
                $userCardDetails = $res["data"];
                $amount = $userCardDetails['data']['amount'] / 100;
                    // Check if payment authorization already exist
                    $payment_auth = [];
                    if(!empty($userCardDetails['data']['authorization'])){
                        $payment_auth = $this->paymentAuthorizationRepository->checkIfExist($userCardDetails);
                    }

                     if ( $payment_auth->count() <= 0) {
                         Log::info( "New Authorization Card" );
                     } else {
                         if (env('APP_ENV') === 'production') {
                             //If It's successful
                             if ($userCardDetails['data']['status'] === "success" && $userCardDetails['data']['customer']['email'] === $request->user()->email) {
                                 //Update Transaction Table
                                 $tx->status = $userCardDetails['data']['status'];
                                 $tx->gw_authorization_code = $userCardDetails['data']['authorization']['authorization_code'];
                                 $tx->amount = $amount;
                                 $tx->reference_model = get_class($payment_auth->first());
                                 $tx->reference_model_id = $payment_auth->first()->id;
                                 $tx->description = 'Card was verified';
                                 $tx->save();

                                 $this->fundWallet ($request, $amount, $tx);
                             }
                             return $this->errorJsonResponse(null, 'Card is already in the system');
                         }
                     }
                //If It's successful, we need to confirm that it's not insufficient fund message we are getting
                if ($userCardDetails['data']['status'] === "success" && $userCardDetails['data']['customer']['email'] === $request->user()->email) {

                    $paymentAuth = $this->paymentAuthorizationRepository->store($request->user(), $ref_code, $userCardDetails);
                    //Update Transaction Table
                    $tx->status = $userCardDetails['data']['status'];
                    $tx->gw_authorization_code = $userCardDetails['data']['authorization']['authorization_code'];
                    $tx->reference_model = get_class($paymentAuth);
                    $tx->reference_model_id = $paymentAuth->id;
                    $tx->amount = $amount;
                    $tx->description = 'Card was verified';
                    $tx->save();

                    $this->fundWallet ($request, $amount, $tx);
                    //Card Notification
                    return $this->successJsonResponse($paymentAuth, 'Card details stored successfully, and your wallet funded');
                }
                return $this->errorJsonResponse(null, 'The transaction was not successful');
            } else {
                return $this->errorJsonResponse(null, 'Invalid Key/Unable to process request');
            }
        } catch (\Exception $error) {
            if (App::environment(['local', 'staging'])) {
                return $this->errorJsonResponse(null, 'An error occurred Details: '. $error->getMessage());
            } else {
                return $this->errorJsonResponse(null, 'An error occurred');
            }
        }
    }
}
