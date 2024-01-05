<?php

use Illuminate\Support\Facades\Route;

Route::resource('ballone/league', 'Ballone\LeagueController', ['as' => 'ballone']);
Route::resource('ballone/club', 'Ballone\ClubController', ['as' => 'ballone']);
Route::resource('ballone/match', 'Ballone\MatchController', ['as' => 'ballone']);

Route::resource('ballone/body', 'Ballone\BodyFeesController', ['as' => 'ballone']);
Route::resource('ballone/maung', 'Ballone\MaungFeesController', ['as' => 'ballone']);

// Route::get('ballone/body/fees/testing', 'Ballone\BodyFeesController@store')->name('ballone.body.fees.add.testing');
Route::get('ballone/body-fees/enabled', 'Ballone\BodyFeesController@bodyFeesEnable')->name('ballone.body-fees.enabled');
Route::get('ballone/maung-fees/enabled', 'Ballone\MaungFeesController@maungFeesEnable')->name('ballone.maung-fees.enabled');

// Ballone Match Create With Maung Fees

Route::get('ballone/maung/fees/add', 'Ballone\MaungFeesController@add')->name('ballone.maung.fees.add');
Route::post('ballone/maung/fees/add', 'Ballone\MaungFeesController@create')->name('ballone.maung.fees.store');

Route::middleware('check_admin')->group(function () {

    // Ballone Maung Limit
    Route::get('ballone/maung-limit', 'Ballone\MaungLimitController@index')->name('ballone.maung-limit.index');
    Route::post('ballone/maung-limit/store', 'Ballone\MaungLimitController@store')->name('ballone.maung-limit.store');

    // Ballone Maung Team Minimum and Maximum Setting
    Route::get('ballone/maung-teams-setting', 'Ballone\MaungLimitController@teams_index')->name('ballone.maung-teams-setting.index');
    Route::post('ballone/maung-teams-setting/store', 'Ballone\MaungLimitController@teams_store')->name('ballone.maung-teams-setting.store');

    // Ballone Maung Za
    Route::get('ballone/maung-za', 'Ballone\MaungZaController@index')->name('ballone.maung-za.index');
    Route::get('ballone/maung-za/show/{id}', 'Ballone\MaungZaController@show')->name('ballone.maung-za.get');
    Route::post('ballone/maung-za/store', 'Ballone\MaungZaController@store')->name('ballone.maung-za.store');
    Route::delete('ballone/maung-za/{id}', 'Ballone\MaungZaController@destroy')->name('ballone.maung-za.delete');

    // Ballone Body Setting
    Route::get('ballone/body-setting', 'Ballone\BodySettingController@index')->name('ballone.body-setting.index');
    Route::post('ballone/body-setting/store', 'Ballone\BodySettingController@store')->name('ballone.body-setting.store');

    // Body /  Maung Cancel
    Route::post('ballone/body-bet/cancel', 'Ballone\ReportDetailController@bodyCancel')->name('ballone.body.refund');
    Route::post('ballone/maung-bet/cancel/{id}', 'Ballone\ReportDetailController@maungCancel')->name('ballone.maung.refund');

    // Match Close . . all fees close
    Route::post('ballone/match/{type}/{id}/{status}', 'Ballone\MatchController@close')->name('ballone.match.close');
});


// Ballone Refund
Route::post('ballone/match-refund/{type}/{id}', 'Ballone\MatchController@refund')->name('ballone.match.refund');
Route::get('ballone/matches-refund', 'Ballone\MatchController@refundHistory')->name('ballone.match.refund.history');

Route::get('matches-history', 'Ballone\MatchController@matchHistory')->name('ballone.match.history');
Route::post('matches-result', 'Ballone\MatchController@updateResult')->name('ballone.match.result');

Route::post('calculate-body-result', 'Ballone\CalculationController@calculateBodyResult')->name('ballone.calculate.body.result');
Route::post('calculate-maung-result', 'Ballone\CalculationController@calculateMaungResult')->name('ballone.calculate.maung.result');

Route::get('get-clubs/{league}', 'Ballone\MatchController@getClubs')->name('ballone.get-clubs');

// Ballone Body Add Result & Calculation
Route::get('ballone-add-result/body/{id}', 'Ballone\AddResultController@body');
Route::post('ballone-add-result/body/{id}', 'Ballone\AddResultController@addBody')->name('calculate.body.result');
Route::post('ballone-calculate-result/body/{id}', 'Ballone\CalculationController@body')->name('ballone.calculate.body.result');

Route::post('ballone-add-result/manual/body/{id}', 'Ballone\AddResultController@addBodyManual')->name('manual.body.result');

// Ballone Maung Add Result & Calculation
Route::get('ballone-add-result/maung/{id}', 'Ballone\AddResultController@maung');
Route::post('ballone-add-result/maung/{id}', 'Ballone\AddResultController@addMaung')->name('calculate.maung.result');
Route::post('ballone-calculate-result/maung/{id}', 'Ballone\CalculationController@maung')->name('ballone.calculate.maung.result');

Route::post('ballone-add-result/manual/{id}', 'Ballone\AddResultController@addManual')->name('manual.add.result');

// Match Report
Route::get('match/report/{id}', 'Ballone\ReportDetailController@index')->name('match.report');
Route::get('match/body-report/{id}/{fee_id}', 'Ballone\ReportDetailController@bodyReport')->name('match.body-report');
Route::get('match/maung-report/{id}/{fee_id}', 'Ballone\ReportDetailController@maungReport')->name('match.maung-report');

Route::get('football/body-detail/{id}', 'Ballone\ReportDetailController@bodyDetail')->name('match.body.detail.report');
Route::get('football/maung-detail/{id}', 'Ballone\ReportDetailController@maungDetail')->name('match.maung.detail.report');
