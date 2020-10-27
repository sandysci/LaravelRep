<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankDetail\CreateRequest;
use App\Services\BankDetailService;
use Illuminate\Http\Request;

class BankDetailController extends Controller
{
    protected BankDetailService $bankDetailService;

    public function __construct(BankDetailService $bankDetailService)
    {
        $this->bankDetailService = $bankDetailService;
    }

    public function index()
    {
        return ApiResponse::responseSuccess([], 'User Bank Details');
    }

    public function resolve()
    {
        if (!request()->query->has('account_number')) {
            return ApiResponse::responseError([], 'The account number is required', 422);
        }
        if (!request()->query->has('bank_code')) {
            return ApiResponse::responseError([], 'The bank code is required', 422);
        }
        $accountNumber = request()->query('account_number');
        $bankCode = request()->query('bank_code');
        $response = $this->bankDetailService->resolveAccount($accountNumber, $bankCode);

        if (!$response->status) {
            return ApiResponse::responseError([], $response->message, 400);
        }

        return ApiResponse::responseSuccess($response->data, $response->message);
    }

    public function store(CreateRequest $request)
    {
        //Check if profile is verified
        if(!request()->user()->userProfile->bvn_verified && $request->bvn) {
            return ApiResponse::responseError([],'Please verify your BVN, or add a ');
        }
        return ApiResponse::responseSuccess();
    }
}
