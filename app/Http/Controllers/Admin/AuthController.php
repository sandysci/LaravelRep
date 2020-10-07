<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        return true;
    }
}
