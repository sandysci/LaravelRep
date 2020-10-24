<?php

namespace App\Domain\Dto\Request\SavingCycle;

class CreateDto
{
    public string $name;
    public float $amount;
    public ?string $targetAmount;
    public string $plan;
    public string $paymentAuth;
    public string $startDate;
    public string $endDate;
    public ?int $hourOfDay;
    public ?int $dayOfWeek;
    public ?int $dayOfMonth;
    public ?string $withdrawalDate;
    public ?string $description;
    public ?string $status;


    public function __construct(
        string $name,
        float $amount,
        ?string $targetAmount,
        string $plan,
        string $paymentAuth,
        string $startDate,
        string $endDate,
        ?int $hourOfDay,
        ?int $dayOfWeek,
        ?int $dayOfMonth,
        ?string $withdrawalDate,
        ?string $description,
        ?string $status
    ) {
        $this->name = $name;
        $this->amount = $amount;
        $this->targetAmount = $targetAmount;
        $this->plan = $plan;
        $this->paymentAuth = $paymentAuth;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->hourOfDay = $hourOfDay;
        $this->dayOfWeek = $dayOfWeek;
        $this->dayOfMonth = $dayOfMonth;
        $this->withdrawalDate = $withdrawalDate;
        $this->description = $description;
        $this->status = $status;
    }
}
