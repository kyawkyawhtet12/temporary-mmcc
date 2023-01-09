<?php

namespace Database\Seeders;

use App\Models\LotteryTime;
use Illuminate\Database\Seeder;

class LotteryTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Thai Lottery Time
        
        LotteryTime::create([
            'time' => '12:01 PM',
            'type' => 0
        ]);
        
        LotteryTime::create([
            'time' => '4:30 PM',
            'type' => 0
        ]);

       
    }
}
