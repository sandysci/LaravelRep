<?php

use App\Http\Controllers\API\v1\HomeController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [HomeController::class, 'user']);
});
