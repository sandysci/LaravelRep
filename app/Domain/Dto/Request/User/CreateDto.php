<?php

namespace App\Domain\Dto\Request\User;

class CreateDto
{
    public string $name;
    public string $email;
    public string $phone;
    public string $phone_country;
    public string $password;
    public string $callbackUrl;
    public ?string $type;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $phone_country,
        string $password,
        string $callbackUrl,
        ?string $type
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->phone_country = $phone_country;
        $this->password = $password;
        $this->callbackUrl = $callbackUrl;
        $this->type = $type;
    }
}
