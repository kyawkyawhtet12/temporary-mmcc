<?php

namespace Database\Seeders;

use App\Models\FootballBodySetting;
use Illuminate\Database\Seeder;

class FootballBodySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new FootballBodySetting();
        $data->percentage = 5;
        $data->min_amount = 1000;
        $data->max_amount = 500000;
        $data->save();
    }
}
