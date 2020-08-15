<?php

namespace App\Console\Commands;

use App\Services\SavingCycleBillingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SavingCycleWeeklyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saving-cycle:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically charge users on a weekly basic';

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
        Log::info('Starting saving-cycle:weekly command');
        //Get current hour
        $hour = (int) Carbon::now()->format('H');
        $day = (int) Carbon::now()->format('d');
        $this->savingCycleBillingService->weeklyBilling($hour, $day);
        Log::info('Done with saving-cycle:weekly command');
        return;
    }
}
