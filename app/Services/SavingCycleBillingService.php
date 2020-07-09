<?php

namespace App\Services;

class SavingCycleBillingService
{
    protected $savingCycleService;
    protected $walletService;
    protected $mailService;

    public function __construct(
        SavingCycleService $savingCycleService,
        WalletService $walletService,
        MailService $mailService
    ) {
        $this->savingCycleService = $savingCycleService;
        $this->walletService = $walletService;
        $this->mailService = $mailService;
    }

    public function dailyBilling()
    {
    }

    public function weeklyBilling()
    {
    }

    public function monthlyBilling()
    {
    }

    public function billUser()
    {
    }
}
