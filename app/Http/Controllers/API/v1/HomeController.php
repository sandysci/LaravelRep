<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return $this->responseSuccess([], 'Welcome to Adashi');
    }

    public function user(Request $request)
    {
        return $this->responseSuccess($request->user()->toArray(), 'Welcome');
    }
}
