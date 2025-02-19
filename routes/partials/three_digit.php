<?php

Route::get('three_lucky_draws', 'ThreeLuckyDrawController@index')->name('threedigits.index');

Route::get('three_winners', 'ThreeWinnerController@index')->name('three_winners.index');

// Route::resource('three_lucky_numbers', 'ThreeLuckyNumberController');
// Route::post('/three_lucky_no_status', 'ThreeLuckyNumberController@UpdateByAjax');


    Route::get('three-lottery', 'EnabledController@threeLotteryStatus')->name('three.changeStatus');

    Route::get('3d-limit_amounts', 'LimitAmountController@limit_3d')->name('3d.limit.amount');
    Route::post('3d-limit-amounts', 'LimitAmountController@limit_3d_post')->name('3d.limit.amount.post');

    Route::get('3d-compensate', 'LimitCompensationController@limit_3d')->name('3d.compensate.amount');
    Route::post('/three_compensate', 'LimitCompensationController@updateThreeCompensate')->name('3d.compensate.amount.post');

    Route::get('/3d-disable', 'ThreeDigitDisableController@index')->name('3d.disable');
    Route::post('three-digits/enabled-all', 'ThreeDigitDisableController@changeThreeDigitEnable')->name('threedigits.enabled-all');
    Route::post('three-digits/disabled-all', 'ThreeDigitDisableController@changeThreeDigitDisable')->name('threedigits.disabled-all');
    Route::post('three-digits/submit-all', 'ThreeDigitDisableController@changeThreeDigitSubmit')->name('threedigits.submit-all');

    Route::get('/three_lucky_number_setting', 'ThreeDigitLuckySettingController@index')->name('3d.lucky-number.index');
    Route::post('/three_lucky_number_setting', 'ThreeDigitLuckySettingController@update')->name('3d.lucky-number.update');
    Route::post('/three_lucky_number_finish', 'ThreeDigitLuckySettingController@approve')->name('3d.lucky-number.approve');


// 3D Results and Report Detail

Route::get('/3d-monthly-report', 'Report\LotteryReportController@monthly_3d')->name('threedigits.monthly-report');
Route::get('/three-digits-results', 'Report\LotteryReportController@three_digits')->name('three-digits.result');
Route::get('/three-digits-results/{id}', 'Report\LotteryReportController@three_digits_detail')->name('three-digits.result.detail');
