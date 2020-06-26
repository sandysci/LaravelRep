<?php

namespace App\Listeners;

use App\Services\TransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTransactionTable
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
     * @param  object  $event
     * @return void
     */
    public function handle($payload, $event)
    {
        if($payload->status !== "success")
        {
            return;
        }

        $transaction = $this->transactionService->findWhere([
            'reference' => $payload->reference]);
        
        if($transaction  === null || $transaction->status === "success")
        {
            return;
        }



    }
}
