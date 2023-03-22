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
        
        if ($request->ajax()) {
            if ($request->agent) {
                if ($request->agent != 'all') {
                    if (!empty($request->from_date)) {
                        $query = AgentPaymentReport::where('agent_id', $request->agent)->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
                    } else {
                        $query = AgentPaymentReport::where('agent_id', $request->agent)->latest()->orderBy('created_at');
                    }
                } else {
                    if (!empty($request->from_date)) {
                        $query = AgentPaymentReport::whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
                    } else {
                        $query = AgentPaymentReport::latest()->orderBy('created_at');
                    }
                }
            } else {
                if (!empty($request->from_date)) {
                    $query = AgentPaymentAllReport::whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
                } else {
                    $query = AgentPaymentAllReport::latest()->orderBy('created_at');
                }
            }

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('deposit', function ($data) {
                        $count = Payment::getDepositCount($data->created_at);
                        return "$data->deposit ($count)";
                    })
                    ->addColumn('withdraw', function ($data) {
                        $count = Cashout::getWithdrawalCount($data->created_at);
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
