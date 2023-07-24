<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\User;
use App\Models\Agent;
use App\Models\WinRecord;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\FootballMaungZa;
use App\Models\FootballMaungGroup;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyFeeResult;
use App\Models\FootballMaungFeeResult;

class CalculationController extends Controller
{
    public function body($id)
    {
        // find match id is exist or not
        $match = FootballMatch::with('bodies')->findOrFail($id);

        // Body Calculation
        $charge_percent = FootballBodySetting::find(1)->percentage;

        foreach ($match->bodies as $body) {
            $result = FootballBodyFeeResult::where('fee_id', $body->fee_id)->first();

            $type = $body->type;
            $percent =  $result->$type;
            $betAmount = $body->bet->amount;

            if( $percent == 0 ){
                // draw
                $status = 3;
                $net_amount = $betAmount;
            }elseif( $percent > 0 ){
                //win
                $status = 1;
                $win_amount = ( $betAmount * $percent / 100 );
                $charge = ($win_amount * $charge_percent ) / 100;
                $net_amount = $betAmount + ( $win_amount - $charge);

            }else{
                //lose
                $status = 2;
                $win_amount = ( $betAmount * $percent / 100 );
                $charge = 0;
                $net_amount = $betAmount + ( $win_amount - $charge);
            }

            $body->user->increment('amount', $net_amount);
            $body->bet->update(['status' => $status , 'net_amount' => $net_amount ]);

            WinRecord::create([
                'user_id' => $body->user_id,
                'agent_id' => $body->agent_id,
                'type' => 'Body',
                'amount' => $net_amount
            ]);
        }

        // Match Calculate finish update
        $match->update([ 'score' => $match->body_temp_score , 'calculate_body' => 1 ]);

        return redirect('/admin/ballone/body')->with('success', '* calculation successfully done.');
    }

    public function maung($id)
    {
        // find match id is exist or not
        $match = FootballMatch::with('maungs')->findOrFail($id);

        // Maung Calculation
        $maungs = FootballMaung::where('match_id', $id)->whereStatus(0)->get();

        foreach ($maungs as $maung) {
            $maungGroup = FootballMaungGroup::with('bet')->find($maung->maung_group_id);

            if ($maungGroup->bet->status == 0) {
                // calculation
                $user = User::find($maung->user_id);
                $result = FootballMaungFeeResult::where('fee_id', $maung->fee_id)->first();
                $type = $maung->type;
                $percent =  $result->$type;

                $betAmount = $maungGroup->bet->net_amount == 0 ?
                                    $maungGroup->bet->amount :
                                    $maungGroup->bet->net_amount;

                if ($percent == 0) {
                    $net_amount = ($maungGroup->bet->net_amount == 0) ?
                                                        $betAmount :
                                                        $maungGroup->bet->net_amount;
                    $maung->update([ 'status' => 3 ]);
                    $maungGroup->bet->update(['net_amount' => $net_amount ]);
                } else {
                    if( $percent == '-100'){
                        $maung->update([ 'status' => 2 ]);
                        $maungGroup->bet->update(['status' => 2 , 'net_amount' => 0]);
                    }else{
                        $amount = $betAmount + ($betAmount * ($percent / 100));
                        $net_amount = $betAmount + ($amount - $betAmount);
                        $maung->update([ 'status' => 1 ]);
                        $maungGroup->bet->update(['net_amount' => $net_amount ]);
                    }
                }

                // တွက်ရန် အသင်း ကျန်မကျန် ရှာ
                $data = FootballMaung::where('maung_group_id', $maung->maung_group_id)
                                    ->where('status', 0)
                                    ->count();

                // မကျန်တော့ရင် အလျော်အစားလုပ်
                if ($data == 0) {
                    $percent = FootballMaungZa::where('teams', $maungGroup->count)->first()->percent;

                    $win = $maungGroup->bet->net_amount;
                    $charge = $win * ($percent / 100);
                    $amount = (int) ($win - $charge);

                    $user->increment('amount', $amount);
                    $maungGroup->bet->update(['status' => 1 , 'net_amount' => $amount ]);

                    if( $amount > $maungGroup->amount){
                        WinRecord::create([
                            'user_id' => $maung->user_id,
                            'agent_id' => $maung->agent_id,
                            'type' => 'Maung',
                            'amount' => $amount
                        ]);
                    }
                }
            }
        }

        // Match Calculate finish update
        $match->update([ 'score' => $match->maung_temp_score , 'calculate_maung' => 1 ]);

        return redirect('/admin/ballone/maung')->with('success', '* calculation successfully done.');
    }

}
