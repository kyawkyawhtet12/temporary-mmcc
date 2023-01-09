<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Http\Controllers\Controller;
use App\Models\FootballBet;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function bodyTodayReport()
    {
        $data = FootballBet::whereNotNull('body_id')->whereDate('created_at', today())->paginate(1);
        // return $data;
        return view("backend.admin.ballone.report.body", compact('data'));
    }

    public function maungTodayReport()
    {
        // return today();
        $data = FootballBet::whereNotNull('maung_group_id')->whereDate('created_at', today())->paginate(1);
        return view("backend.admin.ballone.report.maung", compact('data'));
    }
}
