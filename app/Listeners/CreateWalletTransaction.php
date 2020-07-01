<?php

namespace App\Listeners;

use App\Events\WalletTransactionEvent;
use App\Helpers\RandomNumber;
use App\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateWalletTransaction implements ShouldQueue
{
    protected $transactionService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle the event.
     *
     * @param  WalletTransactionEvent  $event
     * @return void
     */
    public function handle(WalletTransactionEvent $event)
    {
        $reference = 'WT-'.RandomNumber::generateTransactionRef();
  
        $payload = (object) [
            'reference' => $reference,
            'amount' => $event->payload['amount'],
            'description' => "A wallet transaction just occurred",
            'status' => $event->payload['status'],
            'type' => $event->payload['type']
        ];

        $transaction = $this->transactionService->store(
            $payload,
            $event->user,
            $event->wallet
        );

        Log::info('New wallet transaction occurred, Status: '. $transaction->status);
    }
}
