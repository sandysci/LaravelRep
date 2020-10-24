<?php

namespace App\Console\Commands;

use App\Services\SavingCycleBillingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SavingCycleMonthlyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saving-cycle:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically charge users on a monthly basic';

    protected SavingCycleBillingService $savingCycleBillingService;

    /**
     * Create a new command instance.
     *
     * @param SavingCycleBillingService $savingCycleBillingService
     */
    public function __construct(SavingCycleBillingService $savingCycleBillingService)
    {
        parent::__construct();
        $this->savingCycleBillingService = $savingCycleBillingService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Starting saving-cycle:monthly command');
        //Get current Hour/Day
        $hour = (int) Carbon::now()->format('H');
        $day = (int) Carbon::now()->format('d');

        $this->savingCycleBillingService->monthlyBilling($hour, $day);
        Log::info('Done with saving-cycle:monthly command');
    }
}
