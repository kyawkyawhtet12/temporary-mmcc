<?php

namespace Database\Seeders;

use App\Models\TwoDigit;
use Illuminate\Database\Seeder;

class TwoDigitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 99) as $number) {
            TwoDigit::create([
                'number' => str_pad($number, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
