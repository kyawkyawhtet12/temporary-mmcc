<?php

namespace App\Services\TwoDigit;

use App\Models\TwoLuckyNumber;

class TwoLuckyNumberService{

    public function handle()
    {
        TwoLuckyNumber::firstOrCreate(
            ['date' => today(), 'lottery_time_id' => 1],
            ['two_digit_id' => NULL ]
        );

        TwoLuckyNumber::firstOrCreate(
            ['date' => today(), 'lottery_time_id' => 2],
            ['two_digit_id' => NULL ]
        );
    }
}
