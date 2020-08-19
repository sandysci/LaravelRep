<?php

use App\Http\Controllers\API\v1\Auth\ForgotPasswordController;

Route::post('forgot', [ForgotPasswordController::class, 'create']);
Route::put('reset', [PasswordResetController::class, 'reset']);
