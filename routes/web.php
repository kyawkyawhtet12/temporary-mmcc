<?php

use App\Models\ThreeDigit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Record\WinController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Record\CashController;
use App\Http\Controllers\Record\BettingController;
use App\Http\Controllers\Record\RechargeController;
use App\Http\Controllers\Backend\UserPaymentController;
use App\Http\Controllers\Record\BalloneRecordController;
use App\Http\Controllers\Record\LotteryRecordController;
use App\Http\Controllers\Record\TwoDigitRecordController;
use App\Http\Controllers\Backend\Report\UserLogController;
use App\Http\Controllers\Record\ThreeDigitRecordController;
use App\Http\Controllers\Backend\Setting\ReportAmountColorController;
use App\Http\Controllers\Record\DeleteRecordController;

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin');
Route::post('/login/admin', 'Auth\LoginController@adminLogin')->name('admin.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::post('change-password', 'Backend\AdminController@store')->name('change.password');

Route::get('/profile', 'Backend\ProfileController@index')->name('profile');
Route::post('/profile/update', 'Backend\ProfileController@update')->name('profile.update');

Route::group(
    [
        'middleware' => ['auth:admin'],
        'namespace' => 'Backend',
        'prefix' => 'admin'
    ],
    function () {

        Route::get('/dashboard', 'AdminController@index')->name('dashboard.index');

        // 2d
        Route::group([], __DIR__ . '/partials/two_digit.php');

        // 3d
        Route::group([], __DIR__ . '/partials/three_digit.php');

        // Agents
        Route::group([], __DIR__ . '/partials/agent.php');

        // Ballone
        Route::group([], __DIR__ . '/partials/ballone.php');

        Route::middleware('check_admin')->group(function () {

            Route::resource('staff', 'StaffController');
            Route::resource('providers', 'PaymentProviderController');
            Route::resource('banner', 'BannerImageController');
            Route::get('/close-all-bets', 'EnabledController@close_all_bets')->name('close-all-bets');

            Route::post('payment', [UserPaymentController::class, 'store'])->name('payment.store');
            Route::delete('payment/delete', [UserPaymentController::class, 'destroy'])->name('payment.delete');
        });

        Route::resource('users', 'UserController');

        // User Amount Details
        Route::get('amount-details/{id}', [UserLogController::class, 'index']);
        Route::post('amount-details/{id}', [UserLogController::class, 'filter'])->name('amount_details.filter');

        // Game Record
        Route::get('recharge-record', [RechargeController::class, 'index'])->name('recharge.record');

        Route::get('cash-record', [CashController::class, 'index'])->name('cash.record');

        Route::get('betting-record', [BettingController::class, 'index'])->name('betting.record');
        Route::get('betting-record/detail/{id}', [BettingController::class, 'detail'])->name('betting.record.detail');

        Route::delete('betting-record/delete/{id}', [DeleteRecordController::class, 'delete'])->name('betting.record.delete');
        Route::get('betting-record/delete', [DeleteRecordController::class, 'index'])->name('betting.record.delete.history');

        Route::get('win-record', [WinController::class, 'index'])->name('win.record');

        Route::get('3d-record', [ThreeDigitRecordController::class, 'index'])->name('3d.record');
        Route::get('2d-record', [TwoDigitRecordController::class, 'index'])->name('2d.record');

        Route::get('ballone-record/{type}', [BalloneRecordController::class, 'index'])->name('ballone.record');

        Route::controller('Setting\ReportAmountColorController')
            ->as('report-color.setting.')
            ->prefix('report-amount-setting')
            ->group(function () {
                Route::get('{type}', 'index')->name('index');
                Route::post('{type}', 'store')->name('store');
                Route::delete('{id}', 'destroy')->name('destroy');
            });
    }
);
