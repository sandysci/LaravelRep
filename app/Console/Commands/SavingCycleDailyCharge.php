<?php

namespace App\Console\Commands;

use App\Services\SavingCycleBillingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SavingCycleDailyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saving-cycle:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically charge users on a daily basic';

    protected $savingCycleBillingService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SavingCycleBillingService $savingCycleBillingService)
    {
        parent::__construct();

        $this->savingCycleBillingService = $savingCycleBillingService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Starting saving-cycle:daily command');
        //Get current hour
        $hour = (int) Carbon::now()->format('H');
        $this->savingCycleBillingService->dailyBilling($hour);
        Log::info('Done with saving-cycle:daily command');
        return;
    }
}
