<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TransactionService
{
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

    public function getAllTransactions(): Collection
    {
        return Transaction::with('transactionable')->get();
    }
    public function getUserTransactions(User $user): Collection
    {
        $transaction = Transaction::where('user_id', $user->id)->with('transactionable');
        if (request()->query->has('page')) {
            return $this->paginateDate($transaction->jsonPaginate());
        }
        return $transaction->get();
    }

    public function paginateDate($paginatedData)
    {
         $transformedData = $paginatedData->getCollection()
                            ->map(function ($item) {
                                return $item;
                            });
         dd($paginatedData);
//       return collect([
//            $transformedData,
//            $paginatedData->total(),
//            $paginatedData->perPage(),
//            $paginatedData->currentPage(), [
//                'path' => \Request::url(),
//                'query' => [
//                    'page' => $paginatedData->currentPage()
//                ]
//            ]
//        ]);
    }
    public function verifyTransaction()
    {
    }

    public function findWhere(array $conds): ?Transaction
    {
        return Transaction::where($conds)->first();
    }
}
