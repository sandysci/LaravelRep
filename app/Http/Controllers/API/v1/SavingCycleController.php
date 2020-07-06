<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSavingCycleRequest;
use App\Services\CardService;
use App\Services\MailService;
use App\Services\SavingCycleService;
use Illuminate\Http\Request;

class SavingCycleController extends Controller
{
    protected $savingCycleService;
    protected $cardService;
    protected $mailService;

    public function __construct(
        SavingCycleService $savingCycleService,
        CardService $cardService, 
        MailService $mailService
    ){
        $this->savingCycleService = $savingCycleService;
        $this->cardService = $cardService;
        $this->mailService = $mailService;
    }

    public function store(StoreSavingCycleRequest $request)
    {
        try {
            //Find Payment Gateway
            $paymentGateway = $this->cardService->getCard($request->payment_auth);
            if(!$paymentGateway)
            {
                return $this->responseError([], "The payment card is not in our system");
            }

            if(!$paymentGateway->reusable)
            {
                return $this->responseError([], "The card is not reusable");
            }

            $request->status = "paused";
            
            $savingCycle = $this->savingCycleService->store($request->user(), $request, $paymentGateway);

            $this->mailService->sendEmail(
                $request->user()->email, 
                    "You have created a new savings plan", [
                        "introLines" => [ "Kindly, You just created a new savings plan, you will be debited #". $request->amount ],
                        "content" =>   "Thanks, for using Adashi", 
                        "greeting" => "Hello,". $request->user()->name,
                    ]
            );
            return $this->responseSuccess($savingCycle->toArray(), "New saving cycle created");
        } catch(\Exception $e) {
            return $this->responseException($e, 400, $e->getMessage());
        }
    }
}
