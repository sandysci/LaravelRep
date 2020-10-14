<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('v1/phoenix')->group(function () {
    Route::get('login', [AuthController::class, 'getLoginForm']);
    Route::post('login', [AuthController::class, 'login'])->name('admin.login');
    Route::group(['middleware' => ['web', 'role:admin']], function () {
        Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard/transactions', [TransactionController::class, 'index'])->name('admin.get.transactions');
    });
});

Route::fallback(function () {
    return ApiResponse::responseError(
        [
            'Device Info' => request()->header('User-Agent') ?? '',
            'Your IP' => request()->ip() ?? ''
        ],
        'Page Not Found. If error persists, contact developer@adasi.test',
        404
    );
});
