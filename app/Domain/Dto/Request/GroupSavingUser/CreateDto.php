<?php

namespace App\Domain\Dto\Request\GroupSavingUser;

class CreateDto
{
    public array $emails;
    public ?string $callbackUrl;

    public function __construct(
        array $emails,
        ?string $callbackUrl
    ) {
        $this->emails = $emails;
        $this->callbackUrl = $callbackUrl;
    }
}
