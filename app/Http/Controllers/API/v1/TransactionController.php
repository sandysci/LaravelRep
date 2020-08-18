<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\ApiController;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index()
    {
        $transactions = $this->transactionService->getUserTransactions(request()->user());
        return $this->responseSuccess($transactions->toArray(), 'All transactions');
    }
}
