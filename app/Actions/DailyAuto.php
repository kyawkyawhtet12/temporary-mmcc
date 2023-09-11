<?php

namespace App\Actions;

use App\Models\AutoAdd;
use Illuminate\Support\Facades\DB;
use App\Services\Daily\PaymentService;
use App\Services\Daily\LuckyNumberService;

class DailyAuto
{
    public function handle()
    {
        DB::transaction(function () {

            (new LuckyNumberService())->handle();
            (new PaymentService())->handle();

            AutoAdd::first()->update([ 'date' => today() ]);
        });
    }

}
