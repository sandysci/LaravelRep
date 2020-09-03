<?php

namespace App\Domain\Dto\Request\GroupSavingUser;

class CreateDto
{
    public array $emails;

    public function __construct(
        array $emails
    ) {
        $this->emails = $emails;
    }
}
