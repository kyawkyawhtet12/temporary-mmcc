<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThreeDigitCompensation;

class ThreeDigitCompensationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ThreeDigitCompensation::create([
            'compensate' => 500,
            'vote' => 10,
        ]);
    }
}
