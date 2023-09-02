<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Cashout;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\UserPaymentReport;
use App\Http\Controllers\Controller;
use App\Models\AgentPaymentAllReport;
use App\Models\AgentPaymentReport;

class AgentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::all();

        AgentPaymentAllReport::whereDate('created_at', today())->firstOrCreate();



            $query = AgentPaymentAllReport::latest();

            $total_recharge = $query->sum('deposit');
            $total_cash = $query->sum('withdraw');

            $data = $query->get();

        $select_agent = "all";

        return view("backend.report.agent-payments", compact('agents', 'data', 'total_recharge', 'total_cash', 'select_agent'));
    }

    public function search(Request $request)
    {
        // return $request->all();

        $agents = Agent::all();

        if ($request->agent && $request->agent != 'all') {

            AgentPaymentReport::whereDate('created_at', today())
                            ->firstOrCreate([ 'agent_id' => $request->agent ]);

            $query = AgentPaymentReport::where('agent_id', $request->agent)->latest();

        } else {
            $query = AgentPaymentAllReport::latest();
        }

        if (!empty($request->start_date)) {
            $query = $query->whereDate('created_at', '>=', $request->start_date);
        }

        if( !empty($request->end_date)){
            $query = $query->whereDate('created_at' , '<=' , $request->end_date);
        }


        // dd($query->get());

        $total_recharge = $query->sum('deposit');
        $total_cash = $query->sum('withdraw');

        $data = $query->get();

        $select_agent = $request->agent;

        return view("backend.report.agent-payments", compact('agents', 'data', 'total_recharge', 'total_cash', 'select_agent'));
    }
}
