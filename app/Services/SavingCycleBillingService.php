<?php

namespace App\Services;

use App\Helpers\RandomNumber;
use App\Models\SavingCycle;
use Illuminate\Support\Collection;

class SavingCycleBillingService
{
    protected $savingCycleService;
    protected $walletService;
    protected $mailService;
    protected $cardService;
    protected $transactionService;
    protected $savingCycleHistoryService;

    public function __construct(
        SavingCycleService $savingCycleService,
        SavingCycleHistoryService $savingCycleHistoryService,
        WalletService $walletService,
        MailService $mailService,
        CardService $cardService,
        TransactionService $transactionService
    ) {
        $this->savingCycleService = $savingCycleService;
        $this->walletService = $walletService;
        $this->mailService = $mailService;
        $this->cardService = $cardService;
        $this->transactionService = $transactionService;
        $this->savingCycleHistoryService = $savingCycleHistoryService;
    }

    public function dailyBilling(int $hourOfDay): void
    {
        $conditions = [
            'plan' => 'daily',
            'status' => 'active',
            'hour_of_day' => $hourOfDay
        ];
        $plans = $this->savingCycleService->getSavingCycles($conditions, []);
        $this->billUser($plans);
    }

    public function weeklyBilling(int $hourOfDay, int $dayOfWeek): void
    {
        $conditions = [
            'plan' => 'weekly',
            'status' => 'active',
            'hour_of_day' => $hourOfDay,
            'day_of_week' => $dayOfWeek
        ];

        $plans = $this->savingCycleService->getSavingCycles($conditions, []);
        $this->billUser($plans);
    }

    public function monthlyBilling(int $hourOfDay, int $dayOfMonth): void
    {
        //TODO: Handle Febuary 27 / 30th and 31st conditions
        $conditions = [
            'plan' => 'monthly',
            'status' => 'active',
            'hour_of_day' => $hourOfDay,
            'day_of_month' => $dayOfMonth
        ];
        $plans = $this->savingCycleService->getSavingCycles($conditions, []);
        $this->billUser($plans);
    }

    public function billUser(Collection $savingCycles): bool
    {
        foreach ($savingCycles as $savingCycle) {
            //Check payment has been made that day
            $prefix = strtoupper($savingCycle->plan[0] . "SC");
            $reference = $prefix . '-' . RandomNumber::generateTransactionRef();
            // Charge user
            $payload = [];
            $payload["authorization_code"] = $savingCycle->paymentGateway->gw_authorization_code;
            $payload["reference"] = $reference;
            $payload["amount"] = $savingCycle->amount;
            $payload["email"] = $savingCycle->user->email;

            $cardResponse = $this->cardService->pay($payload, "paystack");

            $payload["description"] = "Saving cycle payment";
            $payload["payment_gateway"] = $savingCycle->paymentGateway;
            $payload["type"] = "credit";
            $payload["attempt"] = $savingCycle->attempt + 1;

            if (!$cardResponse->status) {
                $payload["status"] = "failed";
             
                $failedTransDto = (object) $payload;
                $this->transactionService->store($failedTransDto, $savingCycle->user, $savingCycle);
                continue;
            }
            
            $payload["status"] = "success";
            $successTransDto = (object) $payload;
            
            //Create successful transaction entry
            $this->transactionService->store($successTransDto, $savingCycle->user, $savingCycle);

            $this->walletService->incrementBalance($savingCycle->user, $savingCycle->amount);
            $this->storeSavingCycleHistory($savingCycle, $reference);
            $this->emailNotification($savingCycle);
            
            return true;
        }
    }

    public function storeTransaction()
    {
    }

    public function storeSavingCycleHistory(SavingCycle $savingCycle, string $reference): void
    {
        $data = [
            "amount" => $savingCycle->amount,
            "reference" => $reference,
            "status" => "success",
            "attempt" => 1,
            "description" => ucfirst($savingCycle->plan) . " saving cycle payment"
        ];
        $this->savingCycleHistoryService->store($savingCycle->user, $savingCycle->id, $data);
    }

    public function emailNotification(SavingCycle $savingCycle): void
    {
        $this->mailService->sendEmail(
            $savingCycle->user->email,
            "Payment for saving cycle",
            [
                "introLines" => [
                    "You have successfully, make a payment of " . $savingCycle->amount .
                        ",Which is a payment for the saving cycle: " . $savingCycle->name
                ],
                "content" =>   "Thanks, for using Adashi",
                "greeting" => "Hello," . $savingCycle->user->name
            ]
        );
    }
}
