<?php

use App\Http\Controllers\API\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\v1\Auth\LoginController;
use App\Http\Controllers\API\v1\Auth\PasswordResetController;
use App\Http\Controllers\API\v1\Auth\RegisterController;
use App\Http\Controllers\API\v1\Auth\VerificationController;
use App\Http\Controllers\API\v1\CardController;
use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\SavingCycleController;
use App\Http\Controllers\API\v1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //Authentication
    Route::get('/', [HomeController::class, 'index']);

    Route::post('login', [LoginController::class, 'authenticate']);
    Route::post('register', [RegisterController::class, 'store']);

    Route::post('verify/otp', [VerificationController::class, 'verifyOTP']);
    Route::post('verify/resend', [VerificationController::class, 'resendVerificationCode']);

    //Account Verification via Token
    Route::post('verify', [VerificationController::class, 'verify'])->middleware('auth:sanctum');

    Route::prefix('password')->group(function () {
        Route::post('forgot', [ForgotPasswordController::class, 'create']);
        Route::put('reset', [PasswordResetController::class, 'reset']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/user', [HomeController::class, 'user']);

        //Cards
        Route::prefix('cards')->group(function () {
            Route::get('/', [CardController::class, 'index']);
            Route::post('/initialize', [CardController::class, 'initialize']);
            Route::post('/', [CardController::class, 'store']);
        });

        //Individual Savings plan
        Route::prefix('saving-plans')->group(function () {
            Route::get('/', [SavingCycleController::class, 'index']);
            Route::post('/', [SavingCycleController::class, 'store']);
        });

        // Transaction
        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
        });
    });
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
