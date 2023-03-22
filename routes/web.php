<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

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
Route::get('/login/agent', 'Auth\LoginController@showAgentLoginForm')->name('login.agent');
Route::post('/login/agent', 'Auth\LoginController@agentLogin')->name('agent.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('change-password', 'Backend\AdminController@store')->name('change.password');

Route::group(['middleware' => ['auth:admin'], 'namespace' => 'Backend'], function () {
    Route::get('/dashboard', 'AdminController@index')->name('dashboard.index');
});

Route::get('/profile', 'Backend\ProfileController@index')->name('profile');
Route::post('/profile/update', 'Backend\ProfileController@update')->name('profile.update');

Route::group(
    [
    'middleware' => 'auth:agent',
    'namespace' => 'Backend',
],
    function () {
        Route::get('agent-dashboard', 'AgentDashboardController@index')->name('agent.dashboard');
        
        Route::get('agent-profile', 'AgentDashboardController@profile')->name('agent.profile');
        Route::get('agent-withdraw', 'AgentDashboardController@withdraw')->name('agent.withdraw');
        
        // Route::get('/agent/users/list', 'Agent\UserController@index')->name('agent.users.index');
        // Route::post('/agent/users/store', 'Agent\UserController@store')->name('agent.users.store');
        Route::post('agent-store', 'Agent\WithdrawalController@store')->name('agent.withdrawal.store');
        Route::get('/agent-withdrawal', 'Agent\WithdrawalController@index')->name('agent.withdrawal-form');
        Route::get('/agent-withdrawal/history', 'Agent\WithdrawalController@history')->name('agent.withdrawal-history');
    }
);

Route::group(['middleware' => 'auth:agent', 'prefix' => 'agent' ,'namespace' => 'Backend'], function () {
    Route::resource('users', 'Agent\UserController', ['as' => 'agent' ]);
});

Route::group(
    [
    'middleware' => 'auth:admin',
    'namespace' => 'Backend',
    'prefix' => 'admin'
],
    function () {
        Route::get('/lottery-today-report', 'Report\LotteryReportController@today')->name('lottery.today-report');
        Route::get('/ballone-today-report', 'Report\BalloneReportController@today')->name('ballone.today-report');

        Route::resource('users', 'UserController');
        // Route::resource('user-payments', 'PaymentController');
        // Route::post('/paymentstatus', 'PaymentController@UpdateByAjax');

        // Route::resource('cashouts', 'CashoutController');
        // Route::post('changeStatus', 'CashoutController@ChangeTransferStatus')->name('cash.changeStatus');

        Route::resource('providers', 'PaymentProviderController');

        Route::get('two-lottery', 'EnabledController@twoLotteryStatus')->name('two.changeStatus');
        Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');

        Route::resource('three-lottery-close', 'DisableController');

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
        Route::resource('agents', 'AgentController');

        Route::resource('staff', 'StaffController');
        
        Route::resource('agent-deposit', 'AgentDepositController');
        Route::get('agent-deposit/history', 'AgentDepositController@history')->name('agent-deposit.history');
        Route::post('agent-deposit/accept/{id}', 'AgentDepositController@accept');
        Route::post('agent-deposit/reject/{id}', 'AgentDepositController@reject');
        
        Route::resource('agent-withdraw', 'AgentWithdrawController');
        Route::get('agent-withdrawal/history', 'AgentWithdrawController@history')->name('agent-withdraw.history');
        Route::post('agent-withdrawal/accept/{id}', 'AgentWithdrawController@accept');
        Route::post('agent-withdrawal/reject/{id}', 'AgentWithdrawController@reject');

        Route::get('/agent-payment/report/{id}', 'AgentController@payment_report')->name('payment.report');
        // Route::get('/agent-payment-report/search/{id}', 'AgentController@payment_report_search')->name('payment.report.search');

        Route::get('agent-payment-reports', 'Report\AgentPaymentController@index')->name('agent.payment-reports');

        // Route::get('withdrawChangeStatus', 'AgentWithdrawController@ChangeTransferStatus')->name('agent.changeStatus');

        Route::get('agents-three', 'AgentController@threeLuckyDraw')->name('agents.three');
        Route::get('agents-football', 'AgentController@footballLuckyDraw')->name('agents.football');
        Route::get('agent-percentage', 'AgentController@agentPercentage')->name('agent.percentage');
        Route::post('/percentage', 'AgentController@updatePercentage');

        

        Route::get('two-thai-lottery', 'EnabledController@twoThaiLotteryStatus')->name('two.thai.changeStatus');
        Route::get('two-dubai-lottery', 'EnabledController@twoDubaiLotteryStatus')->name('two.dubai.changeStatus');
        Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');
    
        Route::post('two-digits/enabled-all', 'AdminController@changeTwoDigitEnable')->name('twodigits.enabled-all');
        Route::post('two-digits/disabled-all', 'AdminController@changeTwoDigitDisable')->name('twodigits.disabled-all');
        Route::post('two-digits/submit-all', 'AdminController@changeTwoDigitSubmit')->name('twodigits.submit-all');
        
        // Setting

        
        Route::resource('three-lottery-close', 'DisableController');

        Route::get('limit_amounts', 'LimitAmountController@index')->name('limit.amount');
        Route::post('/update-min', 'LimitAmountController@updateMin');
        Route::post('/update-max', 'LimitAmountController@updateMax');

        Route::get('compensate', 'TwoDigitCompensationController@index')->name('compensate.amount');
        Route::post('/two_compensate', 'TwoDigitCompensationController@updateTwoCompensate');
        Route::post('/three_compensate', 'TwoDigitCompensationController@updateThreeCompensate');
        // Route::post('/vote', 'TwoDigitCompensationController@updateVote');

        Route::get('lottery-times', 'LotteryTimeController@index')->name('lottery-time.index');
        Route::get('lottery-times/edit/{id}', 'LotteryTimeController@edit')->name('lottery-time.edit');
        Route::put('lottery-times/edit/{id}', 'LotteryTimeController@update')->name('lottery-time.update');

        Route::resource('banner', 'BannerImageController');
        
        // Ballone
        Route::resource('ballone/league', 'Ballone\LeagueController', ['as' => 'ballone' ]);
        Route::resource('ballone/club', 'Ballone\ClubController', ['as' => 'ballone' ]);
        Route::resource('ballone/match', 'Ballone\MatchController', ['as' => 'ballone' ]);

        Route::resource('ballone/body', 'Ballone\BodyFeesController', ['as' => 'ballone' ]);
        Route::resource('ballone/maung', 'Ballone\MaungFeesController', ['as' => 'ballone' ]);
        
        // Ballone Maung Limit
        Route::get('ballone/maung-limit', 'Ballone\MaungLimitController@index')->name('ballone.maung-limit.index');
        Route::get('ballone/maung-limit/show', 'Ballone\MaungLimitController@show')->name('ballone.maung-limit.get');
        Route::post('ballone/maung-limit/store', 'Ballone\MaungLimitController@store')->name('ballone.maung-limit.store');

        // Ballone Maung Za
        Route::get('ballone/maung-za', 'Ballone\MaungZaController@index')->name('ballone.maung-za.index');
        Route::get('ballone/maung-za/show/{id}', 'Ballone\MaungZaController@show')->name('ballone.maung-za.get');
        Route::post('ballone/maung-za/store', 'Ballone\MaungZaController@store')->name('ballone.maung-za.store');
        Route::delete('ballone/maung-za/{id}', 'Ballone\MaungZaController@destroy')->name('ballone.maung-za.delete');

        // Ballone Body Setting
        Route::get('ballone/body-setting', 'Ballone\BodySettingController@index')->name('ballone.body-setting.index');
        Route::get('ballone/body-setting/show', 'Ballone\BodySettingController@show')->name('ballone.body-setting.get');
        Route::post('ballone/body-setting/store', 'Ballone\BodySettingController@store')->name('ballone.body-setting.store');

        // Ballone Refund
        Route::post('ballone/match/refund/{id}', 'Ballone\MatchController@refund')->name('ballone.match.refund');
        Route::get('ballone/matches-refund', 'Ballone\MatchController@refundHistory')->name('ballone.match.refund.history');
        
        Route::get('matches-history', 'Ballone\MatchController@matchHistory')->name('ballone.match.history');
        Route::post('matches-result', 'Ballone\MatchController@updateResult')->name('ballone.match.result');
        
        Route::post('calculate-body-result', 'Ballone\CalculationController@calculateBodyResult')->name('ballone.calculate.body.result');
        Route::post('calculate-maung-result', 'Ballone\CalculationController@calculateMaungResult')->name('ballone.calculate.maung.result');

        Route::get('get-clubs/{league}', 'Ballone\MatchController@getClubs')->name('ballone.get-clubs');

        // Ballone Bet Report
        Route::get('body-today-report', 'Ballone\ReportController@bodyTodayReport')->name('ballone.body.today-report');
        Route::get('maung-today-report', 'Ballone\ReportController@maungTodayReport')->name('ballone.maung.today-report');

        Route::get('match-body-list', 'Ballone\ReportController@index')->name('ballone.match-body-list');
        Route::get('match-maung-list', 'Ballone\ReportController@maung')->name('ballone.match-maung-list');
        Route::get('match-body-report/{id}', 'Ballone\ReportController@detail')->name('ballone.body.report');
        Route::get('match-maung-report/{id}', 'Ballone\ReportController@detail')->name('ballone.maung.report');

        Route::get('ballone-add-result/{id}', 'Ballone\AddResultController@index');
        Route::post('ballone-add-result/{id}', 'Ballone\AddResultController@add')->name('calculate.result');

        Route::get('ballone-calculate-result/{id}', 'Ballone\CalculationController@index')->name('ballone.calculate.result');

        // Match Report
        Route::get('match/report/{id}', 'Ballone\ReportDetailController@index')->name('match.report');
        Route::get('football/body-detail/{id}', 'Ballone\ReportDetailController@bodyDetail')->name('match.body.detail.report');
        Route::get('football/maung-detail/{id}', 'Ballone\ReportDetailController@maungDetail')->name('match.maung.detail.report');
    }
);
