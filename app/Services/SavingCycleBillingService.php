<?php

namespace App\Services;

use App\Helpers\RandomNumber;
use App\Models\BufferAccount;
use App\Models\SavingCycle;

class SavingCycleBillingService
{
    protected $savingCycleService;
    protected $walletService;
    protected $mailService;
    protected $cardService;
    protected $transactionService;
    protected $savingCycleHistoryService;
    protected $bufferAccountService;

    public function __construct(
        SavingCycleService $savingCycleService,
        SavingCycleHistoryService $savingCycleHistoryService,
        WalletService $walletService,
        MailService $mailService,
        CardService $cardService,
        TransactionService $transactionService,
        BufferAccountService $bufferAccountService
    ) {
        $this->savingCycleService = $savingCycleService;
        $this->walletService = $walletService;
        $this->mailService = $mailService;
        $this->cardService = $cardService;
        $this->transactionService = $transactionService;
        $this->savingCycleHistoryService = $savingCycleHistoryService;
        $this->bufferAccountService = $bufferAccountService;
    }

    public function dailyBilling(int $hourOfDay)
    {
        $conditions = [
            'plan' => 'daily',
            'status' => 'active',
            'hour_of_day' => $hourOfDay
        ];

        $plans = $this->savingCycleService->getSavingCycles($conditions, []);
        $this->billUser($plans);
    }

    public function weeklyBilling(int $hourOfDay, int $dayOfWeek)
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

    public function monthlyBilling(int $hourOfDay, int $dayOfMonth)
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

    public function billUser($savingCycles)
    {
        foreach ($savingCycles as $savingCycle) {
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
            //TODO: Create successful transaction entry
            $this->transactionService->store($successTransDto, $savingCycle->user, $savingCycle);

            $this->fundWalletOrBufferAccount($savingCycle);

            $data = [
                "amount" => $savingCycle->amount,
                "reference" => $reference,
                "status" => "success",
                "attempt" => 1,
                "description" => ucfirst($savingCycle->plan) . " saving cycle payment"
            ];
            $this->savingCycleHistoryService->store($savingCycle->user, $savingCycle->id, $data);

            $this->emailNotification($savingCycle);
            return true;
        }
    }

    public function fundWalletOrBufferAccount(SavingCycle $savingCycle)
    {
        if (count($savingCycle->savingCycleHistories) > 0) {
            $this->walletService->incrementBalance($savingCycle->user, $savingCycle->amount);
        } else {
            $bufferAccountDto = [
                "status" => "success",
                "type" => "credit",
                "description" => "First deduction from daily saving plan into buffer account"
            ];
            $this->bufferAccountService->store(
                $savingCycle->user,
                $savingCycle->amount,
                $savingCycle,
                $bufferAccountDto
            );
        }
    }

    public function emailNotification(SavingCycle $savingCycle)
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
