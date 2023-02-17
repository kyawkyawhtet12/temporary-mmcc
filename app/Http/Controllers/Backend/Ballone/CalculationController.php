<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\User;
use App\Models\Agent;
use App\Models\FootballMatch;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyFeeResult;

class CalculationController extends Controller
{
    public function index($id)
    {
        $match = FootballMatch::findOrFail($id);

        $bodySetting = FootballBodySetting::find(1);
        $bodies = $match->bodies;
        
        foreach ($bodies as $body) {
            $result = FootballBodyFeeResult::where('fee_id', $body->fee_id)->first();
            $type = $body->type;
            $betAmount = $body->bet->amount;
            $percent =  $result->$type;
            $win_amount = $betAmount * ($percent / 100);
            $user = User::find($body->user_id);

            // return $win_amount;

            if ($win_amount > 0) {
                $charge = $win_amount * ($bodySetting->percentage / 100);
                $status = 1;
            } elseif ($win_amount == 0) {
                $charge = 0;
                $status = 3;
            } else {
                $charge = 0;
                $status = 2;
            }

            $win_net_amount = $win_amount - $charge;
            $net_amount = $betAmount + $win_net_amount;
            $user->update(['amount' => $user->amount + $net_amount ]);
            $body->bet->update(['status' => $status , 'net_amount' => $net_amount ]);

            $match->update([ 'score' => $match->temp_score , 'calculate' => 1 ]);
        }
        
        return back();
    }
}
