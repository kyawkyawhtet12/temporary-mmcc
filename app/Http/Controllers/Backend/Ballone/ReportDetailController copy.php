<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\FootballBet;
use App\Models\FootballBody;
use Illuminate\Http\Request;
use App\Models\FootballMaung;
use App\Http\Controllers\Controller;
use App\Services\Ballone\RefundService;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\BettingRecord\Collection\BodyCollection;
use App\Http\Resources\BettingRecord\Collection\MaungCollection;

class ReportDetailController extends Controller
{
    // Body

    public function bodyReport($id, $fee_id)
    {
        if( strpos( url()->previous(), 'page') ) {
            Session::put("prev_route", url()->previous());
        }

        $body = FootballBody::with('user', 'agent', 'bet')->where('match_id', $id)->where('fee_id', $fee_id)->get();

        // return $body->where('type', 'home')->sum('')

        return view('backend.admin.ballone.match.body.detail', compact('body'));
    }

    public function bodyDetail($id)
    {
        $data = FootballBet::where('body_id', $id)->firstOrFail();
        return response()->json(new BodyCollection($data));
    }

    public function bodyCancel(Request $request)
    {
        $body = FootballBody::findOrFail($request->id);
        $body->update([ 'refund' => 1 ]);
        (new RefundService())->history_add($body, $body->bet);
        return response()->json('success');
    }

    // Maung

    public function maungReport($id, $fee_id)
    {
        $maung = FootballMaung::with('user', 'agent','bet.bet')->where('match_id', $id)->where('fee_id', $fee_id)->get();
        return view('backend.admin.ballone.match.maung.detail', compact('maung'));
    }

    public function maungDetail($id)
    {
        $data = FootballMaung::where('maung_group_id', $id)->get();
        return response()->json(MaungCollection::collection($data));
    }

    public function maungCancel(Request $request)
    {
        $maung = FootballMaung::findOrFail($request->id);
        (new RefundService())->maungMatchRefund($maung);
        return response()->json('success');
    }
}
