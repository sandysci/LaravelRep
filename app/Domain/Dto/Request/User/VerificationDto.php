<?php


namespace App\Domain\Dto\Request\User;

class VerificationDto
{
    public string $email;
    public string $token;

    /**
     * VerificationDto constructor.
     * @param string $email
     * @param string $token
     */
    public function __construct(
        string $email,
        string $token
    ) {
        $this->email = $email;
        $this->token = $token;
    }
}
