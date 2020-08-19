<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }
    public function index()
    {
        return ApiResponse::responseSuccess([], 'Welcome to Adashi');
    }

    public function user(Request $request)
    {
        $conds = [ 'id' => $request->user()->id ];
        $user = $this->userService->findOne($conds);
        return ApiResponse::responseSuccess($user->data, $user->message);
    }
}
