<?php

namespace App\Services\Daily;

use App\Models\AutoAdd;
use Illuminate\Support\Facades\DB;

class AutoService
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
