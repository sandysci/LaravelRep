<?php

namespace App\Services;

use App\Helpers\RandomNumber;
use App\Models\SavingCycle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SavingCycleBillingService
{
    protected SavingCycleService $savingCycleService;
    protected WalletService $walletService;
    protected MailService $mailService;
    protected CardService $cardService;
    protected TransactionService $transactionService;
    protected SavingCycleHistoryService $savingCycleHistoryService;

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
        $today = Carbon::now();
        $conditions = [
            'plan' => 'daily',
            'status' => 'active',
            'hour_of_day' => $hourOfDay
        ];
        $plans = SavingCycle::with('savingCycleHistories')
            ->whereRaw('(date(now()) BETWEEN saving_cycles.start_date AND DATE_SUB(saving_cycles.end_date, INTERVAL 0 DAY))')
            ->where($conditions)
            ->get();
        $validPlans = $this->validatePlans($plans);

        $this->billUser($validPlans);
    }

    public function weeklyBilling(int $hourOfDay, int $dayOfWeek): void
    {
        $conditions = [
            'plan' => 'weekly',
            'status' => 'active',
            'hour_of_day' => $hourOfDay,
            'day_of_week' => $dayOfWeek
        ];

        $plans = SavingCycle::with('savingCycleHistories')
                            ->whereRaw('(date(now()) BETWEEN saving_cycles.start_date AND DATE_SUB(saving_cycles.end_date, INTERVAL 0 DAY))')
                            ->where($conditions)
                            ->get();

        $this->billUser($plans);
    }

    public function monthlyBilling(int $hourOfDay, int $dayOfMonth): void
    {
        //TODO: Handle Febuary 27 / 30th and 31st conditions
        // if($dayOfMonth ) {

        // }
        $conditions = [
            'plan' => 'monthly',
            'status' => 'active',
            'hour_of_day' => $hourOfDay,
            'day_of_month' => $dayOfMonth
        ];

        $plans = SavingCycle::with('savingCycleHistories')
                            ->whereRaw('(date(now()) BETWEEN saving_cycles.start_date AND DATE_SUB(saving_cycles.end_date, INTERVAL 0 DAY))')
                            ->where($conditions)
                            ->get();

        $this->billUser($plans);
    }

    public function validatePlans(Collection $plans)
    {
//        $newPlans = collect();
//        foreach ($plans as $plan) {
//            if ($plan->savingCycleHistories->isEmpty()) {
//                continue;
//            }
//
//            foreach ($plan->savingCycleHistories as $savingCycleHistory) {
//                if ($savingCycleHistory->created_at->isToday() && $savingCycleHistory->status === 'success') {
//                    Log::error('Payment has been made already');
//                    continue;
//                }
//            }
//            $newPlans->push($plan);
//        }
////        dd($newPlans);
        return $plans;
    }

    public function billUser(Collection $savingCycles): bool
    {
        if ($savingCycles->isEmpty()) {
            return false;
        }

        foreach ($savingCycles as $savingCycle) {
            //Validate Saving Cycle
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
        }
        return true;
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
