<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Record\WinController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Record\CashController;
use App\Http\Controllers\Record\BettingController;
use App\Http\Controllers\Record\RechargeController;
use App\Http\Controllers\Backend\UserPaymentController;

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
        });

        Route::resource('users', 'UserController');


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
