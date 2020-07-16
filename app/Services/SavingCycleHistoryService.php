<?php

namespace App\Services;

use App\Models\SavingCycleHistory;
use App\Models\User;

class SavingCycleHistoryService
{
    protected $savingCycleService;
    protected $savingCycleHistory;

    public function __construct(
        SavingCycleService $savingCycleService,
        SavingCycleHistory $savingCycleHistory
    ) {
        $this->savingCycleService = $savingCycleService;
        $this->savingCycleHistory = $savingCycleHistory;
    }

    public function store(User $user, string $savingCycleId, $payload): SavingCycleHistory
    {
        $savingCycleHistory = new SavingCycleHistory();
        $savingCycleHistory->user_id = $user->id;
        $savingCycleHistory->saving_cycle_id  = $savingCycleId;
        $savingCycleHistory->reference = $payload["reference"];
        $savingCycleHistory->amount = $payload["amount"];
        $savingCycleHistory->status = $payload["status"];
        $savingCycleHistory->attempt = $payload["attempt"];
        $savingCycleHistory->description = $payload["description"];

        $savingCycleHistory->save();
        
        return $savingCycleHistory;
    }
}
