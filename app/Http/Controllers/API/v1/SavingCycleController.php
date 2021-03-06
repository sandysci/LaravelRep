<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavingCycle\CreateRequest;
use App\Http\Requests\StoreSavingCycleRequest;
use App\Services\CardService;
use App\Services\MailService;
use App\Services\SavingCycleService;
use Illuminate\Http\JsonResponse;

class SavingCycleController extends Controller
{
    protected SavingCycleService $savingCycleService;
    protected CardService $cardService;
    protected MailService $mailService;

    public function __construct(
        SavingCycleService $savingCycleService,
        CardService $cardService,
        MailService $mailService
    ) {
        $this->savingCycleService = $savingCycleService;
        $this->cardService = $cardService;
        $this->mailService = $mailService;
    }

    public function index(): JsonResponse
    {
        $condition = [
            'user_id' => request()->user()->id
        ];
        $savingCycles = $this->savingCycleService->getSavingCycles($condition);

        return ApiResponse::responseSuccess($savingCycles->toArray(), "User's Saving Cycles");
    }

    public function store(CreateRequest $request): JsonResponse
    {
        try {
            //Find Payment Gateway
            $dto = $request->convertToDto();
            $paymentGateway = $this->cardService->getCard($dto->paymentAuth);
            if (!$paymentGateway) {
                return ApiResponse::responseError([], "The payment card is not in our system");
            }

            if (!$paymentGateway->reusable) {
                return ApiResponse::responseError([], "The card is not reusable");
            }

            if (is_null(request()->user()->email_verified_at)) {
                return ApiResponse::responseError([], "Please verify your email");
            }

            $savingCycle = $this->savingCycleService->store(
                request()->user(),
                $dto,
                $paymentGateway
            );

            return ApiResponse::responseCreated($savingCycle->toArray(), "New saving cycle created");
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, $e->getMessage());
        }
    }
}
