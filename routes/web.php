<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'Device Info' => request()->header('User-Agent') ?? '',
        'Your IP' => request()->ip() ?? '',
        'message' => 'Page Not Found. If error persists, contact developer@adashi.com'
    ], 404);
});
