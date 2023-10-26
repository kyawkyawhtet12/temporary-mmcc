<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\FootballMatch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ballone\BodyService;
use App\Services\Ballone\MaungService;
use Illuminate\Support\Facades\Session;

class CalculationController extends Controller
{
    public function body($id, BodyService $bodyService)
    {
        $match = FootballMatch::with('bodies')->findOrFail($id);

        if( $match->calculate_body == 1 ){
            return response()->json(['error' => "Calculation is already done." ]);
        }

        DB::transaction(function () use ($match, $bodyService) {
            $bodyService->calculate($match->bodies);
            $match->update([ 'score' => $match->body_temp_score , 'calculate_body' => 1 ]);
        });

        $url = Session::get("prev_route") ?? '/admin/ballone/body';
        return response()->json(['url' => $url ]);
    }

    public function maung($id, MaungService $maungService)
    {
        $match = FootballMatch::with('maungs')->findOrFail($id);

        if( $match->calculate_maung == 1 ){
            return response()->json(['error' => "Calculation is already done." ]);
        }

        DB::transaction(function () use ($match, $maungService) {
            $maungService->handle($match->maungs);
            $match->update([ 'score' => $match->maung_temp_score , 'calculate_maung' => 1 ]);
        });

        $url = Session::get("prev_route") ?? '/admin/ballone/maung';
        return response()->json(['url' => $url ]);
    }

}
