<?php

namespace App\Domain\Dto\Request\GroupSavingUser;

class EditGroupSavingUserStatusDto
{
    public bool $status;
    public string $groupSavingId;
    public ?string $paymentAuth;

    public function __construct(
        bool $status,
        string $groupSavingId,
        string $paymentAuth
    ) {
        $this->status = $status;
        $this->groupSavingId = $groupSavingId;
        $this->paymentAuth = $paymentAuth;
    }
}
