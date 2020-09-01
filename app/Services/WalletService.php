<?php

namespace App\Services;

use App\Events\WalletTransactionEvent;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Collection;

class WalletService
{
    public function store(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.0000]
        );
    }

    public function getWallet(User $user): ?Wallet
    {
        return Wallet::where('user_id', $user->id)->first();
    }

    public function getWallets(): Collection
    {
        return Wallet::get();
    }

    public function incrementBalance(User $user, float $amount): ?Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet) {
            /**
             * Trigger a Wallet Transaction event,
             * that creates a new entry in the transaction table
             */
            $eventPayload = [
                "amount" => $amount,
                "status" => "success",
                "type" => "credit"
            ];

            event(new WalletTransactionEvent($wallet, $user, $eventPayload));

            $wallet->increment('balance', $amount);
            $wallet->save();
        }

        return $wallet;
    }

    public function decrementBalance(User $user, float $amount): ?Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet) {
            /**
             * Trigger a Wallet Transaction event,
             * that creates a new entry in the transaction table
             */
            $eventPayload = [
                "amount" => $amount,
                "status" => "success",
                "type" => "debit"
            ];

            event(new WalletTransactionEvent($wallet, $user, $eventPayload));

            $wallet->decrement('balance', $amount);
            $wallet->save();
        }

        return $wallet;
    }
}
