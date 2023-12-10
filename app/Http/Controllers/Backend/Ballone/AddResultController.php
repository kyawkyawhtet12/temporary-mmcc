<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyFeeResult;
use App\Models\FootballMaungFeeResult;
use App\Services\Ballone\ResultManualService;
use App\Services\Ballone\ResultService;
use Illuminate\Support\Facades\Session;

class AddResultController extends Controller
{
    // Body Result

    public function body($id)
    {
        $match = FootballMatch::findOrFail($id);

        // if( !Session::get("refresh") && $match->calculate_body == 0 ){
        //     FootballBodyFeeResult::reset($match->allBodyfees);
        // }

        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }

        $match->load('allBodyfees.result');

        return view("backend.admin.ballone.match.body.result", compact("match"));
    }

    public function addBody(Request $request, $id, ResultService $resultService)
    {
        $this->validate($request, [
            'home' => 'required|numeric',
            'away' => 'required|numeric'
        ]);

        $match = FootballMatch::with('allBodyfees.result')->findOrFail($id);

        DB::transaction(function() use ($resultService, $match, $request)
        {
            $resultService->calculate($match->allBodyfees, $request->only('home','away'));
            $match->update([ 'body_temp_score' => "{$request->home}-{$request->away}" ]);
        });

        Session::put('refresh', 'true');
        return back();
    }

    // Maung Result

    public function maung($id)
    {
        $match = FootballMatch::findOrFail($id);

        // if( !Session::get("refresh") && $match->calculate_maung == 0 ){
        //     FootballMaungFeeResult::reset($match->allMaungfees);
        // }

        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }
        $match->load('allMaungfees.result');

        return view("backend.admin.ballone.match.maung.result", compact("match"));
    }

    public function addMaung(Request $request, $id, ResultService $resultService)
    {
        $this->validate($request, [
            'home' => 'required|numeric',
            'away' => 'required|numeric'
        ]);

        $match = FootballMatch::with('allMaungfees.result')->findOrFail($id);

        DB::transaction(function() use ($resultService, $match, $request)
        {
            $resultService->calculate($match->allMaungfees, $request->only('home','away'));
            $match->update([ 'maung_temp_score' => "{$request->home}-{$request->away}" ]);
        });

        Session::put('refresh', 'true');
        return back();
    }



    // Add Result By Manual Input


    public function addManual(Request $request, $result_id)
    {
        if($request->type == "body"){
            $result = FootballBodyFeeResult::findOrFail($result_id);
        }else{
            $result = FootballMaungFeeResult::findOrFail($result_id);
        }

        (new ResultManualService())->handle($request, $result);

        return response()->json(['success' => 'successfully done.']);
    }

}
