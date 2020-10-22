<?php

namespace App\Domain\Dto\Request\UserProfile;

/**
 * Class UpdateDto
 * @package App\Domain\Dto\Request\UserProfile
 */
class UpdateDto
{
    /**
     * @var string|null
     */
    public ?string $firstname;
    /**
     * @var string|null
     */
    public ?string $lastname;
    /**
     * @var string|null
     */
    public ?string $address;
    /**
     * @var string|null
     */
    public ?string $avatar;
    /**
     * @var string|null
     */
    public ?string $bvn;
    /**
     * @var string|null
     */
    public ?string $nextOfKinName;
    /**
     * @var string|null
     */
    public ?string $nextOfKinNumber;
    /**
     * @var string|null
     */
    public ?string $nextOfKinEmail;
    /**
     * @var string|null
     */
    public ?string $nextOfKinRelationship;
    /**
     * @var string|null
     */
    public ?string $dateOfBirth;
    /**
     * @var string|null
     */
    public ?string $meta;

    /**
     * UpdateDto constructor.
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $address
     * @param string|null $avatar
     * @param string|null $bvn
     * @param string|null $nextOfKinName
     * @param string|null $nextOfKinNumber
     * @param string|null $nextOfKinEmail
     * @param string|null $nextOfKinRelationship
     * @param string|null $dateOfBirth
     * @param string|null $meta
     */
    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?string $address,
        ?string $avatar,
        ?string $bvn,
        ?string $nextOfKinName,
        ?string $nextOfKinNumber,
        ?string $nextOfKinEmail,
        ?string $nextOfKinRelationship,
        ?string $dateOfBirth,
        ?string $meta
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->avatar = $avatar;
        $this->bvn = $bvn;
        $this->nextOfKinName = $nextOfKinName;
        $this->nextOfKinNumber = $nextOfKinNumber;
        $this->nextOfKinEmail = $nextOfKinEmail;
        $this->nextOfKinRelationship = $nextOfKinRelationship;
        $this->dateOfBirth = $dateOfBirth;
        $this->meta = $meta;
    }
}
