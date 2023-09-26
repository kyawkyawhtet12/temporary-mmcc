<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\FootballBody;
use Illuminate\Http\Request;
use App\Models\FootballMaung;
use App\Http\Controllers\Controller;
use App\Services\Ballone\RefundService;
use Illuminate\Support\Facades\Session;

class ReportDetailController extends Controller
{
    // Body

    public function bodyReport($id)
    {
        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }

        $body = FootballBody::with('user', 'agent', 'bet')->where('match_id', $id)->get();
        return view('backend.admin.ballone.match.body-detail', compact('body'));
    }

    public function bodyDetail($id)
    {
        $data = FootballBody::with('bet', 'fees', 'match', 'match.home', 'match.away')->findOrFail($id);
        return response()->json($data);
    }

    public function bodyCancel($id)
    {
        $body = FootballBody::findOrFail($id);

        $body->update([ 'refund' => 1 ]);

        (new RefundService())->history_add($body, $body->bet);

        return back()->with(['success' => 'Match Refund successfully.']);
    }

    // Maung

    public function maungReport($id)
    {
        $maung = FootballMaung::with('user', 'agent', 'bet', 'bet.bet')->where('match_id', $id)->get();
        return view('backend.admin.ballone.match.maung-detail', compact('maung'));
    }

    public function maungDetail($id)
    {
        $data = FootballMaung::with('bet', 'fees', 'match', 'match.home', 'match.away', 'bet.bet')
                ->where('maung_group_id', $id)->get();
        return response()->json($data);
    }
}
