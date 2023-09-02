<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballBodyFee;
use App\Models\FootballMaungFee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AddResultController extends Controller
{
    public function body($id)
    {
        $match = FootballMatch::findOrFail($id);

        $arr = explode("=", url()->previous());
        if( array_key_exists(1, $arr)) Session::put("page", $arr[1]);

        return view("backend.admin.ballone.match.body-result", compact("match"));
    }

    public function addBody(Request $request, $id)
    {
        $match = FootballMatch::findOrFail($id);

        $this->validate($request, [
            'home' => 'required',
            'away' => 'required'
        ]);

        $footballBodyFee = FootballBodyFee::where('match_id', $id)->get();

        foreach ($footballBodyFee as $bodyFees) {
            $this->calculation($bodyFees, $request);
        }

        $match->update(['body_temp_score' => "{$request->home} - {$request->away}" ]);

        return back();
    }

    public function maung($id)
    {
        $match = FootballMatch::findOrFail($id);

        $arr = explode("=", url()->previous());
        if( array_key_exists(1, $arr)) Session::put("page", $arr[1]);

        return view("backend.admin.ballone.match.maung-result", compact("match"));
    }

    public function addMaung(Request $request, $id)
    {
        $match = FootballMatch::findOrFail($id);

        $this->validate($request, [
            'home' => 'required',
            'away' => 'required'
        ]);

        $footballMaungFee = FootballMaungFee::where('match_id', $id)->get();

        foreach ($footballMaungFee as $maungFees) {
            $this->calculation($maungFees, $request);
        }

        $match->update(['maung_temp_score' => "{$request->home} - {$request->away}" ]);

        return back();
    }

    public function getFees($fees)
    {
        if (strpos($fees, '+')) {
            return explode('+', $fees);
        }

        if (strpos($fees, '=') || $fees == "=") {
            return explode('=', $fees);
        }

        return explode('-', $fees);
    }

    public function getFeesType($fees)
    {
        if (strpos($fees, '+')) {
            return "+";
        }

        if (strpos($fees, '=') || $fees == "=") {
            return "=";
        }

        return "-";
    }

    private function calculation($allFees, $request)
    {
        $upteam = $allFees->up_team;
        $fees   = $allFees->body;

        $fees_array = $this->getFees($fees);
        $fees_type  = $this->getFeesType($fees);

        $limit   =  (isset($fees_array[0]) && $fees_array[0]) ? $fees_array[0] : 0;
        $percent =  (isset($fees_array[1]) && $fees_array[1]) ? $fees_array[1] : 100;

        if ($upteam == 1) {
            // home
            $net = (int) $request->home - (int) $request->away;
            $real_limit = ($limit == "=" || $limit == "") ? 0 : $limit;

            if ($fees_type === "=") {
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
            $real_limit = ($limit == "=") ? 0 : $limit;

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

        $limit   =  (isset($fees_array[0]) && $fees_array[0]) ? $fees_array[0] : 0;
        $percent =  (isset($fees_array[1]) && $fees_array[1]) ? $fees_array[1] : 100;

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
