<?php

namespace App\Domain\Dto\Request\GroupSaving;

class CreateDto
{
    public string $name;
    public float $amount;
    public string $plan;
    public int $noOfParticipants;
    public string $callbackUrl;
    public ?int $hourOfDay;
    public ?int $dayOfWeek;
    public ?int $dayOfMonth;
    public ?string $description;


    public function __construct(
        string $name,
        float $amount,
        string $plan,
        int $noOfParticipants,
        string $callbackUrl,
        ?int $hourOfDay,
        ?int $dayOfWeek,
        ?int $dayOfMonth,
        ?string $description
    ) {
        $this->name = $name;
        $this->amount = $amount;
        $this->plan = $plan;
        $this->noOfParticipants = $noOfParticipants;
        $this->callbackUrl = $callbackUrl;
        $this->hourOfDay = $hourOfDay;
        $this->dayOfWeek = $dayOfWeek;
        $this->dayOfMonth = $dayOfMonth;
        $this->description = $description;
    }
}
