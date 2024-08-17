<?php

use App\Http\Controllers\Testing\MaungController;

Route::get('maung-bet/calculate/temp_reset', [MaungController::class, 'temp_amount_reset']);

Route::get('maung-bet/calculate/fix', [MaungController::class, 'fix']);

Route::get('maung-bet/calculate/fix_check', [MaungController::class, 'fix_check']);

Route::get('maung-bet/calculate/fix_update', [MaungController::class, 'fix_update']);

Route::get('maung-bet/calculate/user_log', [MaungController::class, 'user_log']);

Route::get('amount-details/{id}/fix', [MaungController::class, 'user_log_fix']);

Route::post('amount-details/{id}/fix', [MaungController::class, 'user_log_add'])->name('amount_details.add');
