<?php

namespace App\Domain\Dto\Request\GroupSaving;

class CreateDto
{
    public string $name;
    public float $amount;
    public string $plan;
    public int $no_of_participant;
    public ?int $hour_of_day;
    public ?int $day_of_week;
    public ?int $day_of_month;
    public ?string $description;


    public function __construct(
        string $name,
        float $amount,
        string $plan,
        int $no_of_participant,
        ?int $hour_of_day,
        ?int $day_of_week,
        ?int $day_of_month,
        ?string $description
    ) {
        $this->name = $name;
        $this->amount = $amount;
        $this->plan = $plan;
        $this->no_of_participant = $no_of_participant;
        $this->hour_of_day = $hour_of_day;
        $this->day_of_week = $day_of_week;
        $this->day_of_month = $day_of_month;
        $this->description = $description;
    }
}
