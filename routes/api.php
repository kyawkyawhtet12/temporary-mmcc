<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\Games\FootballController;
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

Route::post('user/login', [AuthController::class, 'login']);

Route::prefix('user')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);

    //FootBall
    Route::get('/football/body', [FootballController::class, 'bodyMatch']);
    Route::post('/football/body', [FootballController::class, 'bodyStore'])->middleware(['close-all-bets', 'body.check']);

    Route::get('/football/maung', [FootballController::class, 'maungMatch']);
    Route::post('/football/maung', [FootballController::class, 'maungStore'])->middleware(['close-all-bets']);

    Route::get('/football/history', [FootballController::class, 'historyMatch']);
    Route::get('/football/all-history', [FootballController::class, 'allHistoryMatch']);

    Route::get('/football/results', [FootballController::class, 'resultMatch']);
});
