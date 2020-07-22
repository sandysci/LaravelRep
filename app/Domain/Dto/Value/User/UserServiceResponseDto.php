<?php

namespace App\Domain\Dto\Value\User;

/**
 * UserServiceResposeDto - Data transfer object for user service
 */
class UserServiceResponseDto
{
    public bool $status;
    public ?array $data;
    public string $message;
    public ?string $access_token;
    public ?string $token_type;

    /**
     * @param boolean $status
     * @param string $message
     * @param array|null $data
     * @param string|null $access_token
     * @param string|null $token_type
     */
    public function __construct(
        bool $status,
        string $message,
        ?array $data = [],
        ?string $access_token = null,
        ?string $token_type = null
    ) {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->access_token = $access_token;
        $this->token_type = $token_type;
    }
}
