<?php

use App\Http\Controllers\API\v1\HomeController;
use App\Http\Controllers\API\v1\UserProfileController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [HomeController::class, 'user']);
    Route::post('/', [UserProfileController::class, 'update']);
    Route::post('/bvn', [UserProfileController::class, 'storeBvn']);
    Route::post('/bvn/verify', [UserProfileController::class, 'verifyBvn']);
});
