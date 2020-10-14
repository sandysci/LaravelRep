<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupSaving\CreateRequest;
use App\Services\GroupSavingService;
use App\Services\MailService;
use Illuminate\Http\Request;

class GroupSavingController extends Controller
{
    protected GroupSavingService $groupSavingService;
    protected MailService $mailService;

    public function __construct(
        GroupSavingService $groupSavingService,
        MailService $mailService
    ) {
        $this->groupSavingService = $groupSavingService;
        $this->mailService = $mailService;
    }
    public function index()
    {
        $groupSavingPlans = $this->groupSavingService->getAllUserGroupSavings(request()->user());
        return ApiResponse::responseSuccess(
            $groupSavingPlans->toArray(),
            'List of Group saving plans for current user'
        );
    }

    public function store(CreateRequest $request)
    {
        try {
            $groupSaving = $this->groupSavingService->store($request->convertToDto(), request()->user());
            return ApiResponse::responseCreated($groupSaving->toArray(), 'New Group saving created');
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, $e->getMessage());
        }
    }
}
