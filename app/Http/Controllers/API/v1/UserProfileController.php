<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\ResolveBvnRequest;
use App\Http\Requests\UserProfile\UpdateRequest;
use App\Http\Requests\UserProfile\VerifyBvnRequest;
use App\Services\UserProfileService;

class UserProfileController extends Controller
{
    protected UserProfileService $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }
    public function update(UpdateRequest $request)
    {
        $response = $this->userProfileService->update($request->convertToDto(), request()->user());

        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }

        return ApiResponse::responseSuccess($response->data, $response->message);
    }

    // Resolve BVN
    public function storeBvn(ResolveBvnRequest $request)
    {
        $response = $this->userProfileService->resolveBvn($request->convertToDto(), request()->user());

        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }

        return ApiResponse::responseSuccess($response->data, $response->message);
    }

    public function verifyBvn(VerifyBvnRequest $request)
    {
        $response = $this->userProfileService->bvnVerification($request->convertToDto(), request()->user());
        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }

        return ApiResponse::responseSuccess([], $response->message);
    }
}
