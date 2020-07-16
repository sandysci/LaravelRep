<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Services\CardService;
use App\Helpers\RandomNumber;
use App\Http\Requests\PaystackWehookRequest;
use App\Http\Requests\StoreCardRequest;
use App\Services\TransactionService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    protected $cardService;
    protected $transactionService;
    protected $walletService;

    public function __construct(
        CardService $cardService,
        TransactionService $transactionService,
        WalletService $walletService
    ) {
        $this->cardService = $cardService;
        $this->transactionService = $transactionService;
        $this->walletService = $walletService;
    }

    public function initialize(Request $request)
    {
        $response = $this->cardService->initializeCardTrans($request);
        
        return $this->responseSuccess($response->data, 'Initializing Card Transaction');
    }

    public function index()
    {
        $cards = $this->cardService->getUserCards(request()->user());
        if ($cards) {
            $cards = $cards->toArray();
        }
        return $this->responseSuccess($cards, "User's cards");
    }

    public function store(StoreCardRequest $request)
    {
        try {
            $storeCard = $this->cardService->storeCard($request);
            if (!$storeCard->status) {
                return $this->responseError($storeCard->data, $storeCard->message);
            }
            return $this->responseSuccess($storeCard->data, $storeCard->message);
        } catch (\Exception $error) {
            return $this->responseError([], 'An error occurred Details: ' . $error->getMessage());
        }
    }

    public function handleWebhook(Request $request)
    {
        if (!$request->has('channel')) {
            return $this->responseError([], 'Wrong channel');
        }

        $channel = $request->query('channel');
        if ($channel === "paystack") {
            return $this->paystackWebhookHandler($request);
        }

        return $this->responseError([], 'No available channel');
    }

    public function paystackWebhookHandler(PaystackWehookRequest $request)
    {
        $response = $this->cardService->eventHandler($request);
        return $this->responseSuccess([], "Successful request");
    }
}
