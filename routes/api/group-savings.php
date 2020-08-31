<?php

use App\Http\Controllers\API\v1\GroupSavingController;
use App\Http\Controllers\API\v1\GroupSavingUserController;

//Individual Savings plan

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [GroupSavingController::class, 'index']);
    Route::post('/', [GroupSavingController::class, 'store']);
    Route::post('/add-user', [GroupSavingUserController::class, 'store']);
});
