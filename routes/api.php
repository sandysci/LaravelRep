<?php

use App\Http\Controllers\API\v1\Auth\LoginController;
use App\Http\Controllers\API\v1\Auth\RegisterController;
use App\Http\Controllers\API\v1\HomeController;

Route::prefix('v1')->group(function () {
    //Authentication
    Route::get('/', [HomeController::class, 'index']);

    Route::post('login', [LoginController::class, 'authenticate']);
    Route::post('register', [RegisterController::class, 'store']);
});


// Default web URLs
Route::get('login', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthenticated'
    ], 401);
})->name('login');

Route::get('email/verify', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Account not verified, Kindly verify your account'
    ], 422);
})->name('verification.notice');

Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'data' => [],
        'Device Info' => request()->header('User-Agent') ?? '',
        'Your IP' => request()->ip() ?? '',
        'message' => 'Page Not Found. If error persists, contact developer@adasi.test'
    ], 404);
});
