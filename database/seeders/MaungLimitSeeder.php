<?php

namespace Database\Seeders;

use App\Models\FootballMaungLimit;
use App\Models\FootballMaungZa;
use Illuminate\Database\Seeder;

class MaungLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = new FootballMaungLimit();
        $limit->min_teams = 2;
        $limit->max_teams = 12;
        $limit->min_amount = 500;
        $limit->max_amount = 100000;
        $limit->save();

        $za_amount = 1;

        for ($i=1 ; $i < 13; $i++) {
            $za = new FootballMaungZa();
            $za->teams = $i;
            $za->za = 2;
            $za->percent = 10;
            $za->save();
        }
    }
}
