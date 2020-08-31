<?php

namespace App\Domain\Dto\Request\Card;

class CreateDto
{
    public string $reference;
    public string $channel;

    public function __construct(
        string $reference,
        string $channel
    ) {
        $this->reference = $reference;
        $this->channel = $channel;
    }
}
