<?php

namespace App\Services;

class SavingCycleHistory
{
    protected $savingCycleService;

    public function __construct(
        SavingCycleService $savingCycleService
    ) {
        $this->savingCycleService = $savingCycleService;
    }
}
