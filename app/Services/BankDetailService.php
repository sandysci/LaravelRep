<?php
namespace App\Services;

use App\Domain\Dto\Value\BankDetail\BankDetailResponseDto;
use App\Services\Payment\PaystackService;

class BankDetailService
{
    protected PaystackService $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function store()
    {

    }

    public function resolveAccount(string $accountNumber, string $bankCode): BankDetailResponseDto
    {
        $response = $this->paystackService->resolveAccountNumber($accountNumber, $bankCode);

        if (!$response->status) {
            return new BankDetailResponseDto(false, $response->message);
        }
        return new BankDetailResponseDto(true, $response->message, $response->data);
    }
}
