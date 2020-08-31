<?php

namespace App\Domain\Dto\Request\SavingCycle;

class CreateDto
{
    public string $name;
    public int $amount;
    public string $plan;
    public string $payment_auth;
    public string $start_date;
    public string $end_date;
    public ?int $hour_of_day;
    public ?int $day_of_week;
    public ?int $day_of_month;
    public ?string $withdrawal_date;
    public ?string $description;


    public function __construct(
        string $name,
        int $amount,
        string $plan,
        string $payment_auth,
        string $start_date,
        string $end_date,
        ?int $hour_of_day,
        ?int $day_of_week,
        ?int $day_of_month,
        ?string $withdrawal_date,
        ?string $description
    ) {
        $this->name = $name;
        $this->amount = $amount;
        $this->plan = $plan;
        $this->payment_auth = $payment_auth;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->hour_of_day = $hour_of_day;
        $this->day_of_week = $day_of_week;
        $this->day_of_month = $day_of_month;
        $this->withdrawal_date = $withdrawal_date;
        $this->description = $description;
    }
}
