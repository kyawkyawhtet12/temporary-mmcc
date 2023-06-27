<?php

namespace Database\Seeders;

use App\Models\LimitAmount;
use Illuminate\Database\Seeder;

class LimitAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LimitAmount::create([
            'two_min_amount' => 100,
            'two_max_amount' => 100000,
            'three_min_amount' => 100,
            'three_max_amount' => 100000,
        ]);
    }
}
