<?php

namespace App\Services\Ballone;

use App\Models\UserLog;
use App\Models\FootballBody;
use App\Models\BettingRecord;
use App\Models\FootballMaungGroup;
use App\Models\FootballMaungTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BetService
{
    protected $round;

    public function __construct()
    {

        $this->round = DB::table("football_matches")->latest('round')->first()?->round;
    }

    public function bodyStore($request)
    {
        DB::transaction(function () use ($request) {

            $total_amount = array_sum($request->amount);

            $betting_record = BettingRecord::add('Body', count($request->data), $total_amount);

            foreach ($request->data as $x => $dt) {

                $body = FootballBody::create([
                    'match_id' => $dt['match'],
                    'type'     => $dt['type'],
                    'fee_id'   => $dt['fees'],
                    'upteam'   => $dt['upteam']
                ]);

                $body->bet()->create([
                    'amount'   => $request->amount[$x],
                    'net_amount' => 0,
                    'betting_record_id' => $betting_record->id,
                    'round' => $this->round
                ]);
            }

            UserLog::add('Body', $total_amount);

            Auth::user()->decrement('amount', $total_amount);

        });
    }

    public function maungStore($request)
    {
        DB::transaction(function () use ($request) {

            $betting_count = count($request->data);

            $betting_record = BettingRecord::add('Maung', $betting_count, $request->amount);

            $group = FootballMaungGroup::create([
                'count' => $betting_count,
                'round' => $this->round
            ]);

            $betting = [];

            foreach ($request->data as $dt) {

                $group->teams()->create([
                    'match_id' => $dt['match'],
                    'type'     => $dt['type'],
                    'fee_id'   => $dt['fees'],
                    'upteam'   => $dt['upteam']
                ]);

                array_push($betting, ['match' => $dt['match'], 'type' => $dt['type']] );
            }

            $group->bet()->create([
                'amount' => $request->amount,
                'net_amount' => 0,
                'betting_record_id' => $betting_record->id,
                'round' => $this->round
            ]);

            FootballMaungTransaction::create([
                'betting' => json_encode($betting),
                'amount' => $request->amount,
                'user_id' => auth()->id()
            ]);

            UserLog::add('Maung', $request->amount);

            Auth::user()->decrement('amount', $request->amount);
        });
    }
}
