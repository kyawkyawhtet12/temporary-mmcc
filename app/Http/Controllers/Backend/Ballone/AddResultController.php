<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyFee;

class AddResultController extends Controller
{
    public function test($id)
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

        $match->update([ 'score' => $request->home .' '. '-' .' '. $request->away ]);
        $bodySetting  = FootballBodySetting::find(1);

        $footballBodyFee = FootballBodyFee::where('match_id', $id)->get();

        $data = [];
        
        foreach ($footballBodyFee as $bodyFees) {
            $type = $bodyFees->type; // Home or Away , Over or Under
            $upteam = $bodyFees->up_team;
                    

            $fees = $bodyFees->body;
            $fees_array = $this->getFees($fees);
            $fees_type = $this->getFeesType($fees);
            $limit =  $fees_array[0]; // 3
            $percent =  $fees_array[1]; // 3

            if ($upteam == 1) {
                // home

                $net = (int) $request->home - (int) $request->away;

                if ($fees_type === "=") {
                    // return $net;
                    $real_limit = ($limit == "L") ? 0 : $limit;
                    if ($net > $real_limit) {
                        $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                    } else {
                        if ($net == $real_limit) {
                            $bodyFees->result->update(['home' => 0 , 'away' => 0 ]);
                        }
                        if ($net < $real_limit) {
                            $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                        }
                    }
                }

                if ($fees_type === "+") {
                    if ($net < $limit) {
                        $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                    } else {
                        if ($net > $limit) {
                            $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                        }
            
                        if ($net == $limit) {
                            $bodyFees->result->update(['home' => $percent , 'away' => '-'.$percent ]);
                        }
                    }
                }

                if ($fees_type === "-") {
                    if ($net < $limit) {
                        $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                    } else {
                        if ($net > $limit) {
                            $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                        }
                        if ($net == $limit) {
                            $bodyFees->result->update(['home' =>  '-'.$percent , 'away' => $percent ]);
                        }
                    }
                }
            } else {
                // away
                $net = (int) $request->away - (int) $request->home;
                $real_limit = ($limit == "L") ? 0 : $limit;
                if ($fees_type === "=") {
                    if ($net > $real_limit) {
                        $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                    } else {
                        if ($net == $real_limit) {
                            $bodyFees->result->update(['home' => 0 , 'away' => 0 ]);
                        }
                        if ($net < $real_limit) {
                            $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                        }
                    }
                }
                
                if ($fees_type === "+") {
                    if ($net > $real_limit) {
                        $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                    } else {
                        if ($net < $real_limit) {
                            $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                        }
            
                        if ($net == $real_limit) {
                            $bodyFees->result->update(['home' => '-'.$percent , 'away' => $percent ]);
                        }
                    }
                }

                if ($fees_type === "-") {
                    if ($net < $real_limit) {
                        $bodyFees->result->update(['home' => 100 , 'away' => -100 ]);
                    } else {
                        if ($net > $real_limit) {
                            $bodyFees->result->update(['home' => -100 , 'away' => 100 ]);
                        }
            
                        if ($net == $real_limit) {
                            $bodyFees->result->update(['home' => $percent  , 'away' => '-'.$percent ]);
                        }
                    }
                }
            }

            // goals
            $total_goals = (int) $request->home + (int) $request->away;
            $fees = $bodyFees->goals;
            $fees_array = $this->getFees($fees);
            $fees_type = $this->getFeesType($fees);
            $limit =  $fees_array[0]; // 3
            $percent =  $fees_array[1]; // 3

            if ($fees_type === "=") {
                if ($total_goals > $limit) {
                    $bodyFees->result->update(['over' => 100 , 'under' => -100 ]);
                } else {
                    if ($total_goals == $limit) {
                        $bodyFees->result->update(['over' => 0 , 'under' => 0 ]);
                    }
                    if ($total_goals < $limit) {
                        $bodyFees->result->update(['over' => -100 , 'under' => 100 ]);
                    }
                }
            }

            if ($fees_type === "+") {
                if ($total_goals > $limit) {
                    $bodyFees->result->update(['over' => 100 , 'under' => -100 ]);
                } else {
                    if ($total_goals == $limit) {
                        $bodyFees->result->update(['over' => $percent , 'under' => '-'.$percent ]);
                    }
                    if ($total_goals < $limit) {
                        $bodyFees->result->update(['over' => -100 , 'under' => 100 ]);
                    }
                }
            }

            if ($fees_type === "-") {
                if ($total_goals > $limit) {
                    $bodyFees->result->update(['over' => 100 , 'under' => -100 ]);
                } else {
                    if ($total_goals == $limit) {
                        $bodyFees->result->update(['over' => '-'.$percent , 'under' => $percent ]);
                    }
                    if ($total_goals < $limit) {
                        $bodyFees->result->update(['over' => -100 , 'under' => 100 ]);
                    }
                }
            }
        }

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
}
