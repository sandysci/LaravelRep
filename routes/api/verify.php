<?php

use App\Http\Controllers\API\v1\Auth\VerificationController;

//Account Verification via Token
Route::post('/', [VerificationController::class, 'verify'])->middleware('auth:sanctum');
Route::post('otp', [VerificationController::class, 'verifyOTP']);
Route::post('resend', [VerificationController::class, 'resendVerificationCode']);
