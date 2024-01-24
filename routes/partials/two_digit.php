<?php

Route::get('two_lucky_draws', 'TwoLuckyDrawController@index')->name('twodigits.index');
Route::get('two_winners', 'TwoWinnerController@index')->name('two_winners.index');
Route::resource('two_lucky_numbers', 'TwoLuckyNumberController');

Route::post('/two_lucky_no_status', 'TwoLuckyNumberController@UpdateByAjax');


    Route::get('two-lottery', 'EnabledController@twoLotteryStatus')->name('two.changeStatus');

    Route::get('two-thai-lottery', 'EnabledController@twoThaiLotteryStatus')->name('two.thai.changeStatus');

    Route::get('/2d-disable', 'TwoDigitDisableController@index')->name('2d.disable');
    Route::post('/2d-disable', 'TwoDigitDisableController@store')->name('2d.disable.store');
    Route::delete('/2d-disable/{id}', 'TwoDigitDisableController@destroy')->name('2d.disable.delete');

    Route::get('/2d-disable-all', 'TwoDigitDisableController@disable_all')->name('2d.disable.all');
    Route::post('/2d-disable-all', 'TwoDigitDisableController@store_all')->name('2d.disable.all.store');
    Route::delete('/2d-disable-all/{id}', 'TwoDigitDisableController@destroy_all')->name('2d.disable.all.delete');

    // Route::post('two-digits/enabled-all', 'TwoDigitDisableController@changeTwoDigitEnable')->name('twodigits.enabled-all');
    // Route::post('two-digits/disabled-all', 'TwoDigitDisableController@changeTwoDigitDisable')->name('twodigits.disabled-all');
    // Route::post('two-digits/submit-all', 'TwoDigitDisableController@changeTwoDigitSubmit')->name('twodigits.submit-all');

    Route::get('2d-limit_amounts', 'LimitAmountController@limit_2d')->name('2d.limit.amount');
    Route::post('2d-limit-amounts', 'LimitAmountController@limit_2d_post')->name('2d.limit.amount.post');

    Route::get('2d-compensate', 'LimitCompensationController@limit_2d')->name('2d.compensate.amount');
    Route::post('/two_compensate', 'LimitCompensationController@updateTwoCompensate')->name('2d.compensate.amount.post');

    Route::get('lottery-times', 'LotteryTimeController@index')->name('lottery-time.index');
    Route::get('lottery-times/edit/{id}', 'LotteryTimeController@edit')->name('lottery-time.edit');
    Route::put('lottery-times/edit/{id}', 'LotteryTimeController@update')->name('lottery-time.update');


// 2D Results and Report Detail
Route::get('/2d-today-report', 'Report\LotteryReportController@today_2d')->name('twodigits.today-report');
Route::get('/two-digits-results', 'Report\LotteryReportController@two_digits')->name('two-digits.result');
Route::get('/two-digits-results/{id}', 'Report\LotteryReportController@two_digits_detail')->name('two-digits.result.detail')->whereNumber('id');
