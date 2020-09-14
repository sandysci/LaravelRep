<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupSavingUser\CreateRequest;
use App\Http\Requests\GroupSavingUser\UpdateStatusRequest;
use App\Services\GroupSavingService;
use App\Services\GroupSavingUserService;
use Illuminate\Http\Request;

class GroupSavingUserController extends Controller
{
    protected $groupSavingUserService;
    protected $groupSavingService;

    public function __construct(
        GroupSavingUserService $groupSavingUserService,
        GroupSavingService $groupSavingService
    ) {
        $this->groupSavingUserService = $groupSavingUserService;
        $this->groupSavingService = $groupSavingService;
    }

    public function batchStore(CreateRequest $request, string $groupSavingId)
    {
        $dto = $request->convertToDto();

        $response = $this->groupSavingUserService->addUsersToGroupSaving(
            request()->user(),
            $groupSavingId,
            $dto
        );

        if (!$response->status) {
            return ApiResponse::responseError([], $response->message);
        }

        return ApiResponse::responseSuccess($response->data, $response->message);
    }

    public function update()
    {
    }

    public function changeStatus(UpdateStatusRequest $request)
    {
        $dto = $request->convertToDto();
        $response = $this->groupSavingUserService->acceptGroupSavingRequest(request()->user(), $dto);

        if (!$response) {
            return ApiResponse::responseError([], $response->message);
        }

        return ApiResponse::responseSuccess($response->data, $response->message);
    }
}
