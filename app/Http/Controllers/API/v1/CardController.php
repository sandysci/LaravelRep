<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Services\CardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\CreateRequest;
use App\Http\Requests\PaystackWehookRequest;
use App\Http\Requests\StoreCardRequest;
use App\Services\TransactionService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected CardService $cardService;
    protected TransactionService $transactionService;
    protected WalletService $walletService;

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

        return ApiResponse::responseSuccess($response->data, 'Initializing Card Transaction');
    }

    public function index()
    {
        $cards = $this->cardService->getUserCards(request()->user());
        if ($cards) {
            $cards = $cards->toArray();
        }
        return ApiResponse::responseSuccess($cards, "User's cards");
    } 

    public function store(CreateRequest $request)
    {
        try {
            $storeCard = $this->cardService->storeCard($request->convertToDto(), request()->user());

            if (!$storeCard->status) {
                return ApiResponse::responseError($storeCard->data, $storeCard->message);
            }

            return ApiResponse::responseCreated($storeCard->data, $storeCard->message);
        } catch (\Exception $error) {
            return ApiResponse::responseError([], 'An error occurred Details: ' . $error->getMessage());
        }
    }

    public function handleWebhook(Request $request)
    {
        if (!$request->has('channel')) {
            return ApiResponse::responseError([], 'Wrong channel');
        }

        $channel = $request->query('channel');
        if ($channel === "paystack") {
            return $this->paystackWebhookHandler($request);
        }

        return ApiResponse::responseError([], 'No available channel');
    }


    public function verify(CreateRequest $request)
    {
        $response = $this->cardService->verify($request->convertToDto()->reference, $request->convertToDto()->channel);
        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }
        return ApiResponse::responseSuccess($response->data, $response->message);
    }
}
