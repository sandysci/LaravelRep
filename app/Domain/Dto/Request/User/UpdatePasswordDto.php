<?php
namespace App\Domain\Dto\Request\User;

class UpdatePasswordDto
{
    public string $oldPassword;
    public string $newPassword;

    /**
     * VerificationDto constructor.
     * @param string oldPassword
     * @param string newPassword
     */
    public function __construct(
        string $oldPassword,
        string $newPassword
    ) {
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }
}
