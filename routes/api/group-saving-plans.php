<?php

use App\Http\Controllers\API\v1\GroupSavingCycleController;

//Individual Savings plan

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [GroupSavingCycleController::class, 'index']);
    Route::post('/', [GroupSavingCycleController::class, 'store']);
});
