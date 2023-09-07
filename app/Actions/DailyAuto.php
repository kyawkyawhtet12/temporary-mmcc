<?php

namespace App\Actions;

use App\Models\AutoAdd;
use App\Services\Report\AgentPaymentService;
use App\Services\Report\UserPaymentService;
use App\Services\TwoDigit\TwoLuckyNumberService;
use Illuminate\Support\Facades\DB;

class DailyAuto
{
    public function handle()
    {
        if (AutoAdd::whereDate('date',today())->doesntExist()) {

            DB::transaction(function () {

                (new TwoLuckyNumberService())->handle();
                (new AgentPaymentService())->handle();
                (new UserPaymentService())->handle();

                AutoAdd::first()->update([ 'date' => today() ]);
            });
        }
    }

}
