<?php

namespace App\Services\Ballone;

use App\Models\FootballBody;
use App\Models\FootballMaung;
use App\Models\FootballRefundHistory;
use App\Services\Ballone\MaungService;

class RefundService
{
    public function handle($match)
    {
        $this->body($match);
        $this->maung($match);

        $match->update([ 'type' => 0 ]);
    }

    protected function body($match)
    {
        $bodies = FootballBody::with("user", "bet")->where('match_id', $match->id)->get();

        foreach ($bodies as $body) {

            $body->update(['refund' => 1]);

            $this->history_add($body, $body->bet);
        }
    }

    protected function maung($match)
    {
        $maungs = FootballMaung::with('user','bet')->where('match_id', $match->id)->get();

            foreach ($maungs as $maung) {

                $maung->update(['status' => 4, 'refund' => 1]);

                $maung->bet->decrement('count', 1);

                $betting = $maung->bet->bet;

                if( $maung->bet->count == 1 ){
                    $this->history_add($maung, $betting);
                }else{
                    (new MaungService())->calculation($betting, $maung);
                }
            }
    }

    protected function history_add($data, $betting)
    {
        $data->user->increment('amount', (int)$betting->amount);
        $betting->update(['status' => 4]);

        FootballRefundHistory::create([
            'agent_id' => $data->agent_id,
            'user_id'  => $data->user_id,
            'bet_id'   => $betting->id
        ]);
    }

}
