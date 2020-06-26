<?php

use App\Http\Controllers\API\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\API\v1\Auth\LoginController;
use App\Http\Controllers\API\v1\Auth\PasswordResetController;
use App\Http\Controllers\API\v1\Auth\RegisterController;
use App\Http\Controllers\API\v1\Auth\VerificationController;
use App\Http\Controllers\API\v1\CardController;
use App\Http\Controllers\API\v1\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    //Authentication
    Route::get('/', [HomeController::class, 'index']);

    Route::post('login', [LoginController::class, 'authenticate']);
    Route::post('register', [RegisterController::class, 'store']);
   
    Route::post('verify/otp', [VerificationController::class, 'verifyOTP']);
    Route::post('verify/resend', [VerificationController::class, 'resendVerificationCode']);
    //Account Verification via Token
    Route::post('verify', [VerificationController::class, 'verify'])->middleware('auth:sanctum');

    Route::prefix('password')->group(function() {
        Route::post('forgot', [ForgotPasswordController::class, 'create']);
        Route::put('reset', [PasswordResetController::class, 'reset']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::get('/user', [HomeController::class, 'user']);

        //Cards
        Route::prefix('cards')->group(function () {
            Route::get('/', [CardController::class, 'find']);
            Route::post('/initialize', [CardController::class, 'initialize']);
            Route::post('/', [CardController::class, 'store']);
            // Route::put()
        });

        // Route::post('/v')
    });
});


Route::fallback(function(){
    return response()->json([
        'status' => 'error',
        'data' => [],
        'Device Info' => request()->header('User-Agent') ?? '',
        'Your IP' => request ()->ip () ?? '',
        'message' => 'Page Not Found. If error persists, contact developer@adasi.test'
    ], 404);
});
