<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TransactionService {
    protected $transaction;

    public function __construct(Transaction $transaction) 
    {
        $this->transaction = $transaction;
    }

    public function store(Request $request, User $user, ?Model $model): Object 
    {
        $transaction = $this->transaction->create([
            'user_id' => $user->id,
            'reference' => $request->reference,
            'amount' => $request->amount,
            'description' => $request->description,
            'payment_gateway_type' => $request->payment_gateway ? get_class($request->payment_gateway) : null,
            'payment_gateway_id' => $request->payment_gateway->id ?? null,
            'status' => $request->status,
            'type' => $request->type,
            'attempt' => $request->attempt ?? 0,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model->id ?? null
        ]);

        return (object) [
            "status" => true,
            "data" => $transaction->toArray(),
            'message' => "Transaction created"
        ];
    }

    public function findWhere(array $conds) :Transaction
    {
        return $this->transaction->where($conds)->first();
    }
}