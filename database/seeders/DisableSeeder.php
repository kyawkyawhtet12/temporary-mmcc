<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Disable;
use Illuminate\Database\Seeder;

class DisableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disable::create([
            'datetime' => Carbon::now()->addDay(15)->format('Y-m-d H:i')
        ]);
    }
}
