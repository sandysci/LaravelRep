<?php
namespace App\Http\Controllers\Web\Admin;


use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index() {
        return view('admin.dashboard.index');
    }
}
