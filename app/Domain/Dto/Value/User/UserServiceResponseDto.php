<?php

namespace App\Domain\Dto\Value\User;

/**
 * UserServiceResposeDto - Data transfer object for user service
 */
class UserServiceResponseDto
{
    protected bool $status;
    protected ?array $data;
    protected string $message;
    /**
     * @param boolean $status
     * @param string $message
     * @param array|null $data
     */
    public function __construct(bool $status, string $message, ?array $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}
