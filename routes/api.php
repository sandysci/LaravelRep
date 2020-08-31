<?php

use App\Helpers\ApiResponse;
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
    return ApiResponse::responseUnauthorized();
})->name('login');

Route::get('email/verify', function () {
    return ApiResponse::responseError([], 'Account not verified, Kindly verify your account', 422);
})->name('verification.notice');

Route::fallback(function () {
    return ApiResponse::responseError(
        [
            'Device Info' => request()->header('User-Agent') ?? '',
            'Your IP' => request()->ip() ?? ''
        ],
        'Page Not Found. If error persists, contact developer@adasi.test',
        404
    );
});
