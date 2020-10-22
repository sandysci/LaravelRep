<?php
use App\Http\Controllers\API\v1\ImageController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/', [ImageController::class, 'store']);
});
