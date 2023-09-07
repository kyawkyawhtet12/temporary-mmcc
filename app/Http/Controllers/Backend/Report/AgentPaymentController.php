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
        $select_agent = "all";
        $agents = Agent::select('id','name')->get();
        $data = AgentPaymentAllReport::latest()->get();

        return view("backend.report.agent-payments", compact('data', 'select_agent','agents'));
    }

    public function search(Request $request)
    {
        $select_agent = $request->agent;
        $agents = Agent::select('id','name')->get();

        $query = ( $select_agent && $select_agent != 'all' )
                        ? AgentPaymentReport::where('agent_id',  $select_agent)->latest()
                        : AgentPaymentAllReport::latest() ;

        $data = $query->when($request->start_date, function($q) use ($request){
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function($q) use ($request){
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->get();

        return view("backend.report.agent-payments", compact('data','select_agent','agents'));
    }
}
