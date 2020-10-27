<?php
namespace App\Domain\Dto\Value\BankDetail;

class BankDetailResponseDto
{
    public bool $status;
    public string $message;
    public ?array $data;

    /**
     * Construct for CreateCardDto
     *
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
