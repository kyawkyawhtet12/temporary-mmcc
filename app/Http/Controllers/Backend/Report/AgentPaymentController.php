<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Cashout;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\UserPaymentReport;
use App\Models\AgentPaymentReport;
use App\Http\Controllers\Controller;
use App\Models\AgentPaymentAllReport;
use Barryvdh\Debugbar\Facades\Debugbar;

class AgentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        $data = AgentPaymentAllReport::latest()->get();

        $search = [];

        return view("backend.report.agent-payments", compact('data',  'agents', 'search'));
    }

    public function search(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        $query = ( $request->agent_id )
                        ? AgentPaymentReport::whereIn('agent_id',  $request->agent_id)->latest()
                        : AgentPaymentAllReport::latest() ;

        $data = $query->when($request->start_date, function($q) use ($request){
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function($q) use ($request){
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->get();

        $search = $request->only("agent_id", "start_date", "end_date");

        return view("backend.report.agent-payments", compact('data', 'agents','search'));
    }
}
