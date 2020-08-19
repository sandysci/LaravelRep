<?php

use App\Http\Controllers\API\v1\CardController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CardController::class, 'index']);
    Route::post('/', [CardController::class, 'store']);
    Route::post('/initialize', [CardController::class, 'initialize']);
    Route::post('/verify', [CardController::class, 'verify']);
});
