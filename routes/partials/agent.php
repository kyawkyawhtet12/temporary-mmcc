<?php

use Illuminate\Support\Facades\Route;

Route::resource('agents', 'AgentController');

Route::get('agent-deposit', 'AgentDepositController@index')->name('agent-deposit.index');
Route::get('agent-deposit/history', 'AgentDepositController@history')->name('agent-deposit.history');
Route::post('agent-deposit/accept/{id}', 'AgentDepositController@accept');
Route::post('agent-deposit/reject/{id}', 'AgentDepositController@reject');

Route::resource('agent-withdraw', 'AgentWithdrawController');
Route::get('agent-withdrawal/history', 'AgentWithdrawController@history')->name('agent-withdraw.history');
Route::post('agent-withdrawal/accept/{id}', 'AgentWithdrawController@accept');
Route::post('agent-withdrawal/reject/{id}', 'AgentWithdrawController@reject');

Route::get('/agent-payment/report/{id}', 'Report\AgentPaymentController@payment_report')->name('payment.report');
// Route::get('/agent-payment-report/search/{id}', 'AgentController@payment_report_search')->name('payment.report.search');

Route::get('agent-payment-reports', 'Report\AgentPaymentController@index')->name('agent.payment-reports');
Route::post('agent-payment/total-reports', 'Report\AgentPaymentController@total_payments')->name('agent.payment-reports.total');

// Route::get('withdrawChangeStatus', 'AgentWithdrawController@ChangeTransferStatus')->name('agent.changeStatus');

Route::get('agents-three', 'AgentController@threeLuckyDraw')->name('agents.three');
Route::get('agents-football', 'AgentController@footballLuckyDraw')->name('agents.football');
Route::get('agent-percentage', 'AgentController@agentPercentage')->name('agent.percentage');
Route::post('/percentage', 'AgentController@updatePercentage');
