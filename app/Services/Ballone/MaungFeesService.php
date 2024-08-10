<?php

namespace App\Services\Ballone;

use Carbon\Carbon;
use App\Models\FootballMatch;
use Illuminate\Support\Facades\DB;

class MaungFeesService
{
    public static function executeAdd($data)
    {
        return DB::transaction(function () use ($data) {

            foreach ($data['time'] as $key => $time) {

                if ($data['date'][$key] && $data['time'][$key]) {

                    $match = FootballMatch::create([
                        'round'   => $data['round'],
                        'home_no' => $data['home_no'][$key],
                        'away_no' => $data['away_no'][$key],
                        'date_time' => Carbon::createFromFormat("Y-m-d H:i", $data['date'][$key] . $data['time'][$key]),
                        'league_id' => $data['league_id'],
                        'home_id' => $data['home_id'][$key],
                        'away_id' => $data['away_id'][$key],
                        'other'   => ($data['other'] && array_key_exists($key, $data['other'])) ? $data['other'][$key] : 0,
                        'body_limit' => $data['limit_group_id'][$key]
                    ]);

                    $match->matchStatus()->create([ 'admin_id' => auth()->id() ]);

                    $fees = [
                        'body'     => ($data['home_body'][$key]) ?? $data['away_body'][$key],
                        'goals'    => $data['goals'][$key],
                        'up_team'  => ($data['home_body'][$key]) ? 1 : 2,
                        'by'       => auth()->id()
                    ];

                    $bodyFees = $match->bodyFees()->create($fees);

                    $maungFees = $match->maungfees()->create($fees);

                    $bodyFees->result()->create();

                    $maungFees->result()->create();
                }
            }
        });
    }
}
