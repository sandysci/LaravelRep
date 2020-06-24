<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService {
    protected $transaction;

    public function __construct(Transaction $transaction) {
        $this->transaction = $transaction;
    }

    public function store() {
        
    }
}