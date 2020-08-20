<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TransactionService
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function store($request, User $user, ?Model $model): Transaction
    {

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->reference = $request->reference;
        $transaction->amount = $request->amount;
        $transaction->description = $request->description;
        $transaction->payment_gateway_type = isset($request->payment_gateway) ? get_class($request->payment_gateway) : null;
        $transaction->payment_gateway_id = isset($request->payment_gateway) ? $request->payment_gateway->id : null;
        $transaction->status = $request->status;
        $transaction->type = $request->type;
        $transaction->attempt = $request->attempt ?? 0;
        $transaction->transactionable_type = $model ? get_class($model) : null;
        $transaction->transactionable_id = $model->id ?? null;

        $transaction->save();
        return $transaction;
    }

    public function getUserTransactions(User $user): Collection
    {
        return $this->transaction->where('user_id', $user->id)->with('transactionable')->get();
    }

    public function verifyTransaction()
    {
    }

    public function findWhere(array $conds): ?Transaction
    {
        return $this->transaction->where($conds)->first();
    }
}
