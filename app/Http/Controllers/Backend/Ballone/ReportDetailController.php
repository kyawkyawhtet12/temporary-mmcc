<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Http\Controllers\Controller;
use App\Models\FootballBody;
use App\Models\FootballMaung;

class ReportDetailController extends Controller
{
    public function index($id)
    {
        $body = FootballBody::where('match_id', $id)->get();
        $maung = FootballMaung::where('match_id', $id)->get();
        return view('backend.admin.ballone.report.detail', compact('body', 'maung'));
    }
}
