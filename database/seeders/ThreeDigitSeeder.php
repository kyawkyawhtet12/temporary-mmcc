<?php

namespace Database\Seeders;

use App\Models\ThreeDigit;
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
    }
}
