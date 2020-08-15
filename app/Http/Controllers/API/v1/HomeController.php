<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class HomeController extends ApiController
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
