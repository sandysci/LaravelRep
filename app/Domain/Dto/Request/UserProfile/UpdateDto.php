<?php

namespace App\Domain\Dto\Request\UserProfile;

class UpdateDto
{
    public ?string $firstname;
    public ?string $lastname;
    public ?string $address;
    public ?string $avatar;
    public ?string $bvn;
    public ?string $nextOfKinName;
    public ?string $nextOfKinNumber;
    public ?string $meta;

    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?string $address,
        ?string $avatar,
        ?string $bvn,
        ?string $nextOfKinName,
        ?string $nextOfKinNumber,
        ?string $meta
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->avatar = $avatar;
        $this->bvn = $bvn;
        $this->nextOfKinName = $nextOfKinName;
        $this->nextOfKinNumber = $nextOfKinNumber;
        $this->meta = $meta;
    }
}
