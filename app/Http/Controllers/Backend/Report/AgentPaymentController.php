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

        if ($request->ajax()) {
            if ($request->agent && $request->agent != 'all') {

                AgentPaymentReport::whereDate('created_at', today())
                                ->firstOrCreate([ 'agent_id' => $request->agent ]);

                $query = AgentPaymentReport::where('agent_id', $request->agent)->latest();

            } else {
                $query = AgentPaymentAllReport::latest();
            }

            if (!empty($request->from_date)) {
                $query = $query->whereDate('created_at', '>=', $request->from_date)
                                ->whereDate('created_at' , '<=' , $request->to_date);
            }

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('deposit', function ($data) {
                        $count = Payment::getDepositCount($data->created_at, $data->agent_id);
                        return "$data->deposit ($count)";
                    })
                    ->addColumn('withdraw', function ($data) {
                        $count = Cashout::getWithdrawalCount($data->created_at, $data->agent_id);
                        return "$data->withdraw ($count)";
                    })
                    ->addColumn('net', function ($data) {
                        return $data->deposit - $data->withdraw;
                    })
                    ->addColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-m-Y');
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('agent', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['agent'])
                    ->make(true);
        }

        return view("backend.report.agent-payments", compact('agents'));
    }
}
