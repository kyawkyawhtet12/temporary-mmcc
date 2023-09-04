<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Record\WinController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Record\CashController;
use App\Http\Controllers\Record\BettingController;
use App\Http\Controllers\Record\RechargeController;
use App\Http\Controllers\Backend\UserPaymentController;
use App\Http\Controllers\Backend\Report\LotteryReportController;


Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/dashboard', function () {
//     return view('index');
// });


Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin');
Route::post('/login/admin', 'Auth\LoginController@adminLogin')->name('admin.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('change-password', 'Backend\AdminController@store')->name('change.password');

// Route::group(['middleware' => ['auth:admin'], 'namespace' => 'Backend'], function () {
//     Route::get('/dashboard', 'AdminController@index')->name('dashboard.index');
// });

Route::get('/profile', 'Backend\ProfileController@index')->name('profile');
Route::post('/profile/update', 'Backend\ProfileController@update')->name('profile.update');

Route::group(
    [
        'middleware' => ['auth:admin','data_manual'],
        'namespace' => 'Backend',
        'prefix' => 'admin'
    ],
    function () {

        Route::get('/dashboard', 'AdminController@index')->name('dashboard.index');

        // 2d
        Route::get('/2d-today-report', 'Report\LotteryReportController@today_2d')->name('twodigits.today-report');
        Route::get('two_lucky_draws', 'TwoLuckyDrawController@index')->name('twodigits.index');
        Route::get('two_winners', 'TwoWinnerController@index')->name('two_winners.index');
        Route::resource('two_lucky_numbers', 'TwoLuckyNumberController');

        Route::post('/two_lucky_no_status', 'TwoLuckyNumberController@UpdateByAjax');

        Route::middleware('check_admin')->group(function () {

            Route::get('two-lottery', 'EnabledController@twoLotteryStatus')->name('two.changeStatus');

            Route::get('two-thai-lottery', 'EnabledController@twoThaiLotteryStatus')->name('two.thai.changeStatus');

            Route::get('/2d-disable', 'TwoDigitDisableController@index')->name('2d.disable');
            Route::post('two-digits/enabled-all', 'TwoDigitDisableController@changeTwoDigitEnable')->name('twodigits.enabled-all');
            Route::post('two-digits/disabled-all', 'TwoDigitDisableController@changeTwoDigitDisable')->name('twodigits.disabled-all');
            Route::post('two-digits/submit-all', 'TwoDigitDisableController@changeTwoDigitSubmit')->name('twodigits.submit-all');

            Route::get('2d-limit_amounts', 'LimitAmountController@limit_2d')->name('2d.limit.amount');
            Route::post('2d-limit-amounts', 'LimitAmountController@limit_2d_post')->name('2d.limit.amount.post');

            Route::get('2d-compensate', 'LimitCompensationController@limit_2d')->name('2d.compensate.amount');
            Route::post('/two_compensate', 'LimitCompensationController@updateTwoCompensate')->name('2d.compensate.amount.post');

            Route::get('lottery-times', 'LotteryTimeController@index')->name('lottery-time.index');
            Route::get('lottery-times/edit/{id}', 'LotteryTimeController@edit')->name('lottery-time.edit');
            Route::put('lottery-times/edit/{id}', 'LotteryTimeController@update')->name('lottery-time.update');
        });

        // 2D Results and Report Detail
        Route::get('/two-digits-results', [LotteryReportController::class, 'two_digits'])->name('two-digits.result');
        Route::get('/two-digits-results/{id}', [LotteryReportController::class, 'two_digits_detail'])->name('two-digits.result.detail')->whereNumber('id');

        // 3d

        Route::get('/3d-monthly-report', 'Report\LotteryReportController@monthly_3d')->name('threedigits.monthly-report');

        Route::get('three_lucky_draws', 'ThreeLuckyDrawController@index')->name('threedigits.index');

        Route::get('three_winners', 'ThreeWinnerController@index')->name('three_winners.index');

        Route::resource('three_lucky_numbers', 'ThreeLuckyNumberController');
        Route::post('/three_lucky_no_status', 'ThreeLuckyNumberController@UpdateByAjax');

        Route::middleware('check_admin')->group(function () {

            Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');

            Route::get('3d-limit_amounts', 'LimitAmountController@limit_3d')->name('3d.limit.amount');
            Route::post('3d-limit-amounts', 'LimitAmountController@limit_3d_post')->name('3d.limit.amount.post');

            Route::get('3d-compensate', 'LimitCompensationController@limit_3d')->name('3d.compensate.amount');
            Route::post('/three_compensate', 'LimitCompensationController@updateThreeCompensate')->name('3d.compensate.amount.post');

            Route::get('/3d-disable', 'ThreeDigitDisableController@index')->name('3d.disable');
            Route::post('three-digits/enabled-all', 'ThreeDigitDisableController@changeThreeDigitEnable')->name('threedigits.enabled-all');
            Route::post('three-digits/disabled-all', 'ThreeDigitDisableController@changeThreeDigitDisable')->name('threedigits.disabled-all');
            Route::post('three-digits/submit-all', 'ThreeDigitDisableController@changeThreeDigitSubmit')->name('threedigits.submit-all');

            Route::resource('three-lottery-close', 'DisableController');

        });

        // 3D Results and Report Detail
        Route::get('/three-digits-results', [LotteryReportController::class, 'three_digits'])->name('three-digits.result');
        Route::get('/three-digits-results/{id}', [LotteryReportController::class, 'three_digits_detail'])->name('three-digits.result.detail');

        //
        Route::resource('users', 'UserController');

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
        });


        // Game Record
        Route::get('recharge-record', [RechargeController::class, 'index'])->name('recharge.record');
        Route::post('recharge-record', [RechargeController::class, 'search'])->name('recharge.record.search');

        Route::get('cash-record', [CashController::class, 'index'])->name('cash.record');
        Route::post('cash-record', [CashController::class, 'search'])->name('cash.record.search');

        Route::get('betting-record', [BettingController::class, 'index'])->name('betting.record');
        Route::post('betting-record', [BettingController::class, 'search'])->name('betting.record.search');
        Route::get('betting-record/{type}/detail/{id}', [BettingController::class, 'detail'])->name('betting.record.detail');

        Route::get('win-record', [WinController::class, 'index'])->name('win.record');
        Route::post('win-record', [WinController::class, 'search'])->name('win.record.search');
    }
);
