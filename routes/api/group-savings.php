<?php

use App\Http\Controllers\API\v1\GroupSavingController;
use App\Http\Controllers\API\v1\GroupSavingUserController;

//Group Savings plan

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [GroupSavingController::class, 'index']);
    Route::post('/', [GroupSavingController::class, 'store']);
    Route::get('/{groupSavingId}/participants', [GroupSavingUserController::class, 'index']);
    Route::post('/{groupSavingId}/participants', [GroupSavingUserController::class, 'batchStore']);
    Route::put('/{groupSavingId}/participants/{participantEmail}', [GroupSavingUserController::class, 'update']);
    Route::put('/{groupSavingId}/join', [GroupSavingUserController::class, 'changeStatus']);
});
