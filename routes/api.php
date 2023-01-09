<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AgentDepositController;
use App\Http\Controllers\Api\AgentWithdrawalController;
use App\Http\Controllers\Api\Auth\AgentController as AgentAuthController;

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

Route::post('/v1/login', [AgentAuthController::class , 'login']);

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/profile', [AgentController::class , 'profile'])->name('api.user');
    Route::post('/profile', [AgentController::class , 'profile_update'])->name('api.user.update');
    Route::get('/dashboard', [AgentController::class , 'dashboard']);

    Route::post('/logout', [AgentAuthController::class , 'logout']);

    Route::get('/deposit/payments', [PaymentController::class, 'deposit']);
    Route::get('/deposit/history', [AgentDepositController::class, 'index']);
    Route::post('/deposit/request', [AgentDepositController::class, 'store']);
    
    Route::get('/withdrawal/payments', [PaymentController::class, 'withdrawal']);
    Route::get('/withdrawal/history', [AgentWithdrawalController::class, 'index']);
    Route::post('/withdrawal/request', [AgentWithdrawalController::class, 'store']);

    // Agent
});
