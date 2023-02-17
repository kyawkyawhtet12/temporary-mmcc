<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballBodyFee;
use App\Models\FootballMaungFee;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;

class AddResultController extends Controller
{
    public function index($id)
    {
        $match = FootballMatch::findOrFail($id);
        return view("backend.admin.ballone.match.result", compact("match"));
        // if (!$match->score) {
        //     return view("backend.admin.ballone.match.result", compact("match"));
        // }
    }
    
    public function add(Request $request, $id)
    {
        // return $request->all();

        $match = FootballMatch::findOrFail($id);

        $match->update([ 'temp_score' => $request->home .' '. '-' .' '. $request->away ]);

        $footballBodyFee = FootballBodyFee::where('match_id', $id)->get();
        $footballMaungFee = FootballMaungFee::where('match_id', $id)->get();
        
        
        foreach ($footballBodyFee as $bodyFees) {
            $this->calculation($bodyFees, $request);
        }

        foreach ($footballMaungFee as $maungFees) {
            $this->calculation($maungFees, $request);
        }

        $match->update(['calculate' => 0]);

        return back();
    }

    public function getFees($fees)
    {
        if (strpos($fees, '+')) {
            return explode('+', $fees);
        } elseif (strpos($fees, '-')) {
            return explode('-', $fees);
        } else {
            return explode('=', $fees);
        }
    }

    public function getFeesType($fees)
    {
        if (strpos($fees, '+')) {
            return "+";
        } elseif (strpos($fees, '-')) {
            return "-";
        } else {
            return "=";
        }
    }

    public function calculation($allFees, $request)
    {
        $type = $allFees->type; // Home or Away , Over or Under
        $upteam = $allFees->up_team;
                    
        $fees = $allFees->body;
        $fees_array = $this->getFees($fees);
        $fees_type = $this->getFeesType($fees);
        $limit =  $fees_array[0]; // 3
        $percent =  $fees_array[1]; // 3

        if ($upteam == 1) {
            // home

            $net = (int) $request->home - (int) $request->away;

            $real_limit = ($limit == "L") ? 0 : $limit;
            if ($fees_type === "=") {
                // return $net;
                if ($net > $real_limit) {
                    $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                } else {
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' => 0 , 'away' => 0 ]);
                    }
                    if ($net < $real_limit) {
                        $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                    }
                }
            }

            if ($fees_type === "+") {
                if ($net < $real_limit) {
                    $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                } else {
                    if ($net > $real_limit) {
                        $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                    }
            
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' => $percent , 'away' => '-'.$percent ]);
                    }
                }
            }

            if ($fees_type === "-") {
                if ($net < $real_limit) {
                    $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                } else {
                    if ($net > $real_limit) {
                        $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                    }
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' =>  '-'.$percent , 'away' => $percent ]);
                    }
                }
            }
        } else {
            // away
            $net = (int) $request->away - (int) $request->home;
            $real_limit = ($limit == "L") ? 0 : $limit;
            if ($fees_type === "=") {
                if ($net > $real_limit) {
                    $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                } else {
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' => 0 , 'away' => 0 ]);
                    }
                    if ($net < $real_limit) {
                        $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                    }
                }
            }
                
            if ($fees_type === "+") {
                if ($net > $real_limit) {
                    $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                } else {
                    if ($net < $real_limit) {
                        $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                    }
            
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' => '-'.$percent , 'away' => $percent ]);
                    }
                }
            }

            if ($fees_type === "-") {
                if ($net < $real_limit) {
                    $allFees->result->update(['home' => 100 , 'away' => -100 ]);
                } else {
                    if ($net > $real_limit) {
                        $allFees->result->update(['home' => -100 , 'away' => 100 ]);
                    }
            
                    if ($net == $real_limit) {
                        $allFees->result->update(['home' => $percent  , 'away' => '-'.$percent ]);
                    }
                }
            }
        }

        // goals
        $total_goals = (int) $request->home + (int) $request->away;
        $fees = $allFees->goals;
        $fees_array = $this->getFees($fees);
        $fees_type = $this->getFeesType($fees);
        $limit =  $fees_array[0]; // 3
        $percent =  $fees_array[1]; // 3

        if ($fees_type === "=") {
            if ($total_goals > $limit) {
                $allFees->result->update(['over' => 100 , 'under' => -100 ]);
            } else {
                if ($total_goals == $limit) {
                    $allFees->result->update(['over' => 0 , 'under' => 0 ]);
                }
                if ($total_goals < $limit) {
                    $allFees->result->update(['over' => -100 , 'under' => 100 ]);
                }
            }
        }

        if ($fees_type === "+") {
            if ($total_goals > $limit) {
                $allFees->result->update(['over' => 100 , 'under' => -100 ]);
            } else {
                if ($total_goals == $limit) {
                    $allFees->result->update(['over' => $percent , 'under' => '-'.$percent ]);
                }
                if ($total_goals < $limit) {
                    $allFees->result->update(['over' => -100 , 'under' => 100 ]);
                }
            }
        }

        if ($fees_type === "-") {
            if ($total_goals > $limit) {
                $allFees->result->update(['over' => 100 , 'under' => -100 ]);
            } else {
                if ($total_goals == $limit) {
                    $allFees->result->update(['over' => '-'.$percent , 'under' => $percent ]);
                }
                if ($total_goals < $limit) {
                    $allFees->result->update(['over' => -100 , 'under' => 100 ]);
                }
            }
        }
    }
}
