<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index()
    {
        $transactions = $this->transactionService->getUserTransactions(request()->user());
        return ApiResponse::responseSuccess($transactions->toArray(), 'All transactions');
    }

    public function verify()
    {
        $transaction = $this->transactionService->verifyTransaction();
    }
}
