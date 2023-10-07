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
        DB::transaction(function () use ($id, $bodyService) {
            // find match id is exist or not
            $match = FootballMatch::with('bodies','bodies.fees','bodies.bet','bodies.agent','bodies.user')->findOrFail($id);

            // Body Calculation
            $bodyService->calculate($match->bodies);

            // Match Calculate finish update
            $match->update([ 'score' => $match->body_temp_score , 'calculate_body' => 1 ]);
        });

        $url = Session::get("prev_route") ?? '/admin/ballone/body';

        // return redirect($url);
        return response()->json(['url' => $url ]);
    }

    public function maung($id, MaungService $maungService)
    {
        DB::transaction(function () use ($id, $maungService) {
            // find match id is exist or not
            $match = FootballMatch::with('maungs','maungs.fees','maungs.bet','maungs.agent','maungs.user')->findOrFail($id);

            // Maung Calculation
            $maungService->handle($match->maungs);

            // Match Calculate finish update
            $match->update([ 'score' => $match->maung_temp_score , 'calculate_maung' => 1 ]);
        });

        $url = Session::get("prev_route") ?? '/admin/ballone/maung';

        // return redirect($url);
        return response()->json(['url' => $url ]);
    }

}
