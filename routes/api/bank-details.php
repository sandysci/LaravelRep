<?php

use App\Http\Controllers\API\v1\BankDetailController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [BankDetailController::class, 'index']);
    Route::get('/resolve', [BankDetailController::class, 'resolve']);
    Route::post('/', [BankDetailController::class, 'store']);
});
