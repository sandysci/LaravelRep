<?php

use App\Http\Controllers\API\v1\TransactionController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [TransactionController::class, 'index']);
});
