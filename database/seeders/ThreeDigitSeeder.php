<?php

namespace Database\Seeders;

use App\Models\ThreeDigit;
use App\Models\ThreeLuckyNumber;
use Illuminate\Database\Seeder;

class ThreeDigitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 999) as $number) {
            ThreeDigit::create([
                'number' => str_pad($number, 3, '0', STR_PAD_LEFT),
            ]);
        }

        ThreeLuckyNumber::create([
            'round' => 1,
            'date' => today()->format('Y-m-d'),
            'status' => 'Pending'
        ]);
    }
}
