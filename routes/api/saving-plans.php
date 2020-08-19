<?php

use App\Http\Controllers\API\v1\SavingCycleController;

//Individual Savings plan

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [SavingCycleController::class, 'index']);
    Route::post('/', [SavingCycleController::class, 'store']);
});
