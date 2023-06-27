<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Backend\UserPaymentController;

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
    return redirect(route('login'));
});

Auth::routes();

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('index');
});


Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin');
Route::post('/login/admin', 'Auth\LoginController@adminLogin')->name('admin.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('change-password', 'Backend\AdminController@store')->name('change.password');

Route::group(['middleware' => ['auth:admin'], 'namespace' => 'Backend'], function () {
    Route::get('/dashboard', 'AdminController@index')->name('dashboard.index');
});

Route::get('/profile', 'Backend\ProfileController@index')->name('profile');
Route::post('/profile/update', 'Backend\ProfileController@update')->name('profile.update');

Route::group(
    [
    'middleware' => 'auth:admin',
    'namespace' => 'Backend',
    'prefix' => 'admin'
],
    function () {
        Route::get('/2d-today-report', 'Report\LotteryReportController@today_2d')->name('twodigits.today-report');
        Route::get('/3d-monthly-report', 'Report\LotteryReportController@monthly_3d')->name('threedigits.monthly-report');

        Route::resource('users', 'UserController');

        Route::resource('providers', 'PaymentProviderController');

        Route::get('two-lottery', 'EnabledController@twoLotteryStatus')->name('two.changeStatus');
        Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');

        Route::post('two-digits/enabled-all', 'AdminController@changeTwoDigitEnable')->name('twodigits.enabled-all');
        Route::post('two-digits/disabled-all', 'AdminController@changeTwoDigitDisable')->name('twodigits.disabled-all');
        Route::post('two-digits/submit-all', 'AdminController@changeTwoDigitSubmit')->name('twodigits.submit-all');

        Route::resource('two_lucky_numbers', 'TwoLuckyNumberController');
        Route::resource('three_lucky_numbers', 'ThreeLuckyNumberController');

        Route::post('/two_lucky_no_status', 'TwoLuckyNumberController@UpdateByAjax');
        Route::post('/three_lucky_no_status', 'ThreeLuckyNumberController@UpdateByAjax');

        Route::get('three_lucky_draws', 'ThreeLuckyDrawController@index')->name('threedigits.index');
        Route::get('two_lucky_draws', 'TwoLuckyDrawController@index')->name('twodigits.index');

        // Winners
        Route::get('two_winners', 'TwoWinnerController@index')->name('two_winners.index');
        Route::get('three_winners', 'ThreeWinnerController@index')->name('three_winners.index');

        // Agents
        Route::group([], __DIR__ . '/partials/agent.php');

        // Ballone
        Route::group([], __DIR__ . '/partials/ballone.php');


        // 2d

        Route::get('two-thai-lottery', 'EnabledController@twoThaiLotteryStatus')->name('two.thai.changeStatus');

        Route::get('/2d-disable', 'TwoDigitDisableController@index')->name('2d.disable');
        Route::post('two-digits/enabled-all', 'TwoDigitDisableController@changeTwoDigitEnable')->name('twodigits.enabled-all');
        Route::post('two-digits/disabled-all', 'TwoDigitDisableController@changeTwoDigitDisable')->name('twodigits.disabled-all');
        Route::post('two-digits/submit-all', 'TwoDigitDisableController@changeTwoDigitSubmit')->name('twodigits.submit-all');

        Route::get('2d-limit_amounts', 'LimitAmountController@limit_2d')->name('2d.limit.amount');
        Route::post('2d-update-min', 'LimitAmountController@updateMin_2d');
        Route::post('2d-update-max', 'LimitAmountController@updateMax_2d');

        Route::get('2d-compensate', 'LimitCompensationController@limit_2d')->name('2d.compensate.amount');
        Route::post('/two_compensate', 'LimitCompensationController@updateTwoCompensate');

        Route::get('lottery-times', 'LotteryTimeController@index')->name('lottery-time.index');
        Route::get('lottery-times/edit/{id}', 'LotteryTimeController@edit')->name('lottery-time.edit');
        Route::put('lottery-times/edit/{id}', 'LotteryTimeController@update')->name('lottery-time.update');

        // 3d

        Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');

        Route::get('3d-limit_amounts', 'LimitAmountController@limit_3d')->name('3d.limit.amount');
        Route::post('3d-update-min', 'LimitAmountController@updateMin_3d');
        Route::post('3d-update-max', 'LimitAmountController@updateMax_3d');

        Route::get('3d-compensate', 'LimitCompensationController@limit_3d')->name('3d.compensate.amount');
        Route::post('/three_compensate', 'LimitCompensationController@updateThreeCompensate');

        Route::get('/3d-disable', 'ThreeDigitDisableController@index')->name('3d.disable');
        Route::post('/3d-disable', 'ThreeDigitDisableController@store')->name('3d.disable.post');
        Route::delete('/3d-disable/{id}', 'ThreeDigitDisableController@enable')->name('3d.disable.delete');

        Route::resource('three-lottery-close', 'DisableController');
        //

        Route::resource('banner', 'BannerImageController');

        Route::post('payment', [UserPaymentController::class, 'store'])->name('payment.store');


        Route::get('/close-all-bets', 'EnabledController@close_all_bets')->name('close-all-bets');
    }
);
