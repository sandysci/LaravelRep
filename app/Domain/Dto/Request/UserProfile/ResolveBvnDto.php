<?php

namespace App\Domain\Dto\Request\UserProfile;

class ResolveBvnDto
{
    public string $bvn;

    public function __construct(
        string $bvn
    ) {
        $this->bvn = $bvn;
    }
}
