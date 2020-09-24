<?php

namespace App\Domain\Dto\Request\UserProfile;

class VerifyBvnDto
{
    public string $bvn;
    public string $otp;

    public function __construct(
        string $bvn,
        string $otp
    ) {
        $this->bvn = $bvn;
        $this->otp = $otp;
    }
}
