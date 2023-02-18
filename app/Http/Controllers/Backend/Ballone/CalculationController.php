<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\User;
use App\Models\Agent;
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
    public function index($id)
    {
        $match = FootballMatch::findOrFail($id);
        $bodySetting = FootballBodySetting::find(1);
        $charge = 0;

        // Body Calculation
        foreach ($match->bodies as $body) {
            $result = FootballBodyFeeResult::where('fee_id', $body->fee_id)->first();
            $type = $body->type;
            $percent =  $result->$type;
            $betAmount = $body->bet->amount;
            $win_amount = $betAmount * ($percent / 100);
            $user = User::find($body->user_id);

            if ($win_amount > 0) {
                $charge = $win_amount * ($bodySetting->percentage / 100);
                $status = 1;
            } elseif ($win_amount == 0) {
                $status = 3;
            } else {
                $status = 2;
            }
            
            $net_amount = $betAmount + ($win_amount - $charge);
            $user->increment('amount', $net_amount);
            $body->bet->update(['status' => $status , 'net_amount' => $net_amount ]);
        }

        // Maung Calculation
        $footballMaung = FootballMaung::where('match_id', $id)->whereStatus(0)->get();

        foreach ($footballMaung as $maung) {
            $maungGroup = FootballMaungGroup::with('bet')->find($maung->maung_group_id);

            if ($maungGroup->bet->status == 0) {
                // calculation
                $user = User::find($maung->user_id);
                $result = FootballMaungFeeResult::where('fee_id', $maung->fee_id)->first();
                $type = $maung->type;
                $percent =  $result->$type;
                
                $user = User::find($maung->user_id);
                $betAmount = $maungGroup->bet->net_amount == 0 ? $maungGroup->bet->amount : $maungGroup->bet->net_amount;

                if ($percent == 0) {
                    $net_amount = ($maungGroup->bet->net_amount == 0) ? $betAmount : $maungGroup->bet->net_amount;
                    $maung->update([ 'status' => 3 ]);
                    $maungGroup->decrement('count', 1);
                    $maungGroup->bet->update(['net_amount' => $net_amount ]);
                } else {
                    $win_amount = $betAmount * ($percent / 100);
                    
                    if ($win_amount > 0) {
                        $amount = $betAmount + ($betAmount * ($percent / 100));
                        $net_amount = $betAmount + ($amount - $betAmount);
                        $maung->update([ 'status' => 1 ]);
                        $maungGroup->bet->update(['net_amount' => $net_amount ]);
                    } else {
                        $maung->update([ 'status' => 2 ]);
                        $maungGroup->bet->update(['status' => 2 , 'net_amount' => 0]);
                    }
                }

                $data = FootballMaung::where('maung_group_id', $maung->maung_group_id)->where('status', 0)->count();

                if ($data == 0) {
                    $maungZa = FootballMaungZa::where('teams', $maungGroup->count)->first();
                    $win = $maungGroup->bet->net_amount;
                    $percent = $win * ($maungZa->percent / 100);
                    $amount = $win - $percent;
                    $user->increment('amount', $amount);
                    $maungGroup->bet->update(['status' => 1 , 'net_amount' => $amount ]);
                }
            }
        }

        $match->update([ 'score' => $match->temp_score , 'calculate' => 1 ]);
        
        return back()->with('success', '* calculation successfully done.');
    }
}
