<?php

namespace App\Services\Daily;

use App\Models\AutoAdd;
use Illuminate\Support\Facades\DB;
use App\Services\Daily\PaymentReportService;

class AutoService
{
    public function handle()
    {
        DB::transaction(function () {

            (new PaymentReportService())->handle();

            AutoAdd::first()->update([ 'date' => today() ]);

        });
    }
}
