<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TwoDigitCompensation;

class TwoDigitCompensationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TwoDigitCompensation::create([
            'compensate' => 85,
        ]);
    }
}
