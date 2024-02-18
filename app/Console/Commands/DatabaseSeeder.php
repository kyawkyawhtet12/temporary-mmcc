<?php

namespace App\Console\Commands;

use App\Models\BadgeColorSetting;
use Illuminate\Console\Command;

class DatabaseSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db-default-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Database Default Data Add';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $two_digit_colors = [
            '10000' => '#04b76b',
            '30000' => '#e9881f',
            '100000' => '#f0230d'
        ];

        foreach( $two_digit_colors as $amount => $color ){

            BadgeColorSetting::create( [
                'name'       => '2D',
                'color'      => $color,
                'max_amount' => $amount
            ]);

        }

        $three_digit_colors = [
            '5000' => '#62ca39',
            '10000' => '#04b76b',
            '30000' => '#e9881f',
            '100000' => '#f0230d'
        ];

        foreach( $three_digit_colors as $amount => $color ){

            BadgeColorSetting::create( [
                'name'       => '3D',
                'color'      => $color,
                'max_amount' => $amount
            ]);

        }
    }
}
