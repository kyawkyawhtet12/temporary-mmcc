<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\User;
use App\Models\Agent;
use App\Models\FootballBody;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\FootballMaungZa;
use App\Models\FootballMaungGroup;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;

class CalculationController extends Controller
{
    public function calculateBodyResult(Request $request)
    {
        // Calculation Body Winner
        $footballBody = FootballBody::where('match_id', $request->match_id)
                                    ->whereStatus(0)->get();
        $bodySetting = FootballBodySetting::find(1);

        foreach ($footballBody as $body) {
            //
            if ($body->user_id) {
                $user = User::find($body->user_id);
            } else {
                $user = Agent::find($body->agent_id);
            }

            $club_id = $request->club_id[$body->fee_id];
            $goals = $request->goals[$body->fee_id];
            $body_percentage = $request->body_percentage[$body->fee_id];
            $goals_percentage = $request->goals_percentage[$body->fee_id];

            $betAmount = $body->bet->amount;

            if ($goals === 'draw' || $club_id === 'draw') {
                // Draw
                $user->update(['amount' => $user->amount + $betAmount ]);
                $body->bet->update(['status' => 3 , 'net_amount' => $betAmount ]);
            } elseif ($body->type == 'home' || $body->type == 'away') {
                // Home or Away
                $team = ($body->type == 'home') ? $body->match->home_id : $body->match->away_id;

                $percent = $body_percentage === null ? 100 : $body_percentage ;
               
                if ($team == $club_id) {
                    $win_amount = $betAmount * ($percent / 100);
                    $win_net_amount = $win_amount - ($win_amount * ($bodySetting->percentage / 100));
                    $net_amount = $betAmount + $win_net_amount;
                    $user->update(['amount' => $user->amount + $net_amount ]);
                    $body->bet->update(['status' => 1 , 'net_amount' => $net_amount ]);
                } else {
                    $amount = $betAmount - ($betAmount * ($percent / 100));
                    $user->update(['amount' => $user->amount + $amount ]);
                    $body->bet->update(['status' => 2 , 'net_amount' => $amount ]);
                }
            } else {
                // Goals Over or Under
                $percent = $goals_percentage === null ? 100 : $goals_percentage ;

                if ($body->type == $goals) {
                    $win_amount = $betAmount * ($percent / 100);
                    $win_net_amount = $win_amount - ($win_amount * ($bodySetting->percentage / 100));
                    $net_amount = $betAmount + $win_net_amount;
                    $user->update(['amount' => $user->amount + $net_amount ]);
                    $body->bet->update(['status' => 1 , 'net_amount' => $net_amount ]);
                } else {
                    $amount = $betAmount - ($betAmount * ($percent / 100));
                    $user->update(['amount' => $user->amount + $amount ]);
                    $body->bet->update(['status' => 2 , 'net_amount' => $amount ]);
                }
            }

            // $body->bet->update(['status' => 1]);
        }

        FootballMatch::find($request->match_id)->update(['calculate_body' => 1 ]);

        return response()->json(['success'=>'Match saved successfully.']);
    }

    public function calculateMaungResult(Request $request)
    {
        // Calculation Maung Winner
        $footballMaung = FootballMaung::where('match_id', $request->match_id)
                                        ->whereStatus(0)->get();
        
        foreach ($footballMaung as $maung) {
            $maungGroup = FootballMaungGroup::with('bet')->find($maung->maung_group_id);

            if ($maungGroup->bet->status == 0) {
                // calculation
                
                if ($maung->user_id) {
                    $user = User::find($maung->user_id);
                } else {
                    $user = Agent::find($maung->agent_id);
                }

                $club_id = $request->club_id[$maung->fee_id];
                $goals = $request->goals[$maung->fee_id];
                $body_percentage = $request->body_percentage[$maung->fee_id];
                $goals_percentage = $request->goals_percentage[$maung->fee_id];

                $betAmount = $maungGroup->bet->net_amount == 0 ? $maungGroup->bet->amount : $maungGroup->bet->net_amount;

                if ($goals === 'draw' || $club_id === 'draw') {
                    // Draw
                    $maung->update([ 'status' => 3 ]);
                    $maungGroup->decrement('count', 1);
                } elseif ($maung->type == 'home' || $maung->type == 'away') {
                    // Home or Away
                    $team = ($maung->type == 'home') ? $maung->match->home_id : $maung->match->away_id;

                    $percent = $body_percentage === null ? 100 : $body_percentage ;
               
                    if ($team == $club_id) {
                        $amount = $betAmount + ($betAmount * ($percent / 100));
                        $net_amount = $betAmount + ($amount - $betAmount);
                        $maung->update(['status'=> 1]);
                        $maungGroup->bet->update(['net_amount' => $net_amount ]);
                    } else {
                        if ($percent == 100) {
                            $maung->update(['status' => 2]);
                            $maungGroup->bet->update(['status' => 2 , 'net_amount' => 0]);
                        } else {
                            $amount = $betAmount - ($betAmount * ($percent / 100));
                            $net_amount = $betAmount + ($amount - $betAmount);
                            $maung->update(['status'=> 1]);
                            $maungGroup->bet->update(['net_amount' => $net_amount ]);
                        }
                    }
                } else {
                    $percent = $goals_percentage === null ? 100 : $goals_percentage ;

                    if ($maung->type == $goals) {
                        $amount = $betAmount + ($betAmount * ($percent / 100));
                        $net_amount = $betAmount + ($amount - $betAmount);
                        $maung->update(['status'=> 1]);
                        $maungGroup->bet->update(['net_amount' => $net_amount ]);
                    } else {
                        if ($percent == 100) {
                            $maung->update(['status' => 2]);
                            $maungGroup->bet->update(['status' => 2 , 'net_amount' => 0]);
                        } else {
                            $amount = $betAmount - ($betAmount * ($percent / 100));
                            $net_amount = $betAmount + ($amount - $betAmount);
                            $maung->update(['status'=> 1]);
                            $maungGroup->bet->update(['net_amount' => $net_amount ]);
                        }
                    }
                }

                $data = FootballMaung::where('maung_group_id', $maung->maung_group_id)
                                    ->where('status', 0)
                                    ->count();

                if ($data == 0) {
                    $maungZa = FootballMaungZa::where('teams', $maungGroup->count)->first();
                    // $win = $maungZa->za * $maungGroup->bet->amount;
                    $win = $maungGroup->bet->net_amount;
                    $percent = $win * ($maungZa->percent / 100);
                    $amount = $win - $percent;
                    $user->update(['amount' => $user->amount +  $amount ]);
                    $maungGroup->bet->update(['status' => 1 , 'net_amount' => $amount ]);
                }
            }
        }

        FootballMatch::find($request->match_id)->update(['calculate_maung' => 1 ]);

        return response()->json(['success'=>'Match saved successfully.']);
    }
}
