<?php

namespace App\Domain\Dto\Request\GroupSavingUser;

class CreateDto
{
    public string $groupSavingId;
    public string $email;

    public function __construct(
        string $groupSavingId,
        string $email
    ) {
        $this->groupSavingId = $groupSavingId;
        $this->email = $email;
    }
}
