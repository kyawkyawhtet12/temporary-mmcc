<?php

namespace Database\Seeders;

use App\Models\ThreeDigit;
use App\Models\ThreeDigitSetting;
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

        ThreeDigitSetting::create([
            'date' => '2023-10-16',
            'start_time' => today(),
            'end_time' => '2023-10-16 14:45:00'
        ]);

        ThreeLuckyNumber::create([
            'status' => 'Pending',
            'date_id' => 1
        ]);
    }
}
