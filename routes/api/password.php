<?php

use App\Http\Controllers\API\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\v1\Auth\PasswordResetController;

Route::post('forgot', [ForgotPasswordController::class, 'create']);
Route::put('reset', [PasswordResetController::class, 'reset']);
