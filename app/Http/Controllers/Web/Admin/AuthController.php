<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    public function getLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
           if(!Auth::user()->hasRole('admin')) {
               Auth::logout();
               Session::flash('error', "Unauthorised");
               return view('admin.auth.login');
            }
            return redirect()->intended(route('admin.dashboard'));
        } else {
            Session::flash('error', "Invalid request!");
            return view('admin.auth.login');
        }
    }
}
