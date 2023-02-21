<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\League;
use Illuminate\Database\Seeder;

class BalloneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $league = new League();
        $league->name = "England Premier League";
        $league->country = "United Kingdom";
        $league->save();

        $league = new League();
        $league->name = "Spain Laliga";
        $league->country = "Spain";
        $league->save();

        $league = new League();
        $league->name = "Series A";
        $league->country = "Italy";
        $league->save();

        $league = new League();
        $league->name = "FA Cup";
        $league->country = "United Kingdom";
        $league->save();

        $league = new League();
        $league->name = "Champion League";
        $league->country = "United Kingdom";
        $league->save();

        //

        $club = new Club();
        $club->name = "Manchester United";
        $club->league_id = 1;
        $club->save();

        $club = new Club();
        $club->name = "Manchester City";
        $club->league_id = 1;
        $club->save();

        $club = new Club();
        $club->name = "Arsenal";
        $club->league_id = 1;
        $club->save();

        $club = new Club();
        $club->name = "Liverpool";
        $club->league_id = 1;
        $club->save();

        $club = new Club();
        $club->name = "Chelsea";
        $club->league_id = 1;
        $club->save();

        $club = new Club();
        $club->name = "Real Madrid";
        $club->league_id = 2;
        $club->save();

        $club = new Club();
        $club->name = "Barcelona";
        $club->league_id = 2;
        $club->save();

        $club = new Club();
        $club->name = "AC Milan";
        $club->league_id = 3;
        $club->save();

        $club = new Club();
        $club->name = "Inter Milan";
        $club->league_id = 3;
        $club->save();

        $club = new Club();
        $club->name = "Roma";
        $club->league_id = 3;
        $club->save();

        $club = new Club();
        $club->name = "Manchester United";
        $club->league_id = 4;
        $club->save();

        $club = new Club();
        $club->name = "Manchester United";
        $club->league_id = 5;
        $club->save();
    }
}
