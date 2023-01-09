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
            'min_amount' => 100,
            'max_amount' => 100000,
        ]);
    }
}
