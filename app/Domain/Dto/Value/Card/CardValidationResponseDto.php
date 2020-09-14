<?php

namespace App\Domain\Dto\Value\Card;

use App\Models\Card;

class CardValidationResponseDto
{
    public bool $status;
    public ?Card $card;
    public string $message;

    public function __construct(bool $status, ?Card $card, string $message)
    {
        $this->status = $status;
        $this->card = $card;
        $this->message = $message;
    }
}
