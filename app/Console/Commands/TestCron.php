<?php

namespace App\Console\Commands;

use App\Services\MailService;
use Illuminate\Console\Command;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-cron:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically charge users on a weekly basic';

    protected $mailService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MailService $mailService)
    {
        parent::__construct();
        $this->mailService = $mailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        //Get current hour
        $this->mailService->sendEmail(
            'victoralagwu@gmail.com',
            "Testing cron",
            [
                "introLines" => ["Kindly, You just created a new savings plan, you will be debited #" . $savingCycle->amount],
                "content" =>   "Testing cron tab",
                "greeting" => "Hello,"
            ]
        );
        return;
    }
}
