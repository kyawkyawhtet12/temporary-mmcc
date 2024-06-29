<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Report\UserPaymentService;

class AgentPaymentController extends Controller
{
    protected $userPaymentService;

    public function __construct(UserPaymentService $userPaymentService)
    {
        $this->userPaymentService = $userPaymentService;
    }

    public function index(Request $request, $id)
    {
        // return request()->id;

        $agents = Agent::select('id', 'name')
                        ->when($id != 'all' ?? NULL, function ($q){
                            return $q->where('id', request()->id);
                        })
                        ->get();

        $filter =  request()->only([ 'agent_id', 'start_date', 'end_date' ]);

        if ($request->ajax()) {

            $query = $this->userPaymentService->executeRecord($filter);

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return [
                        'deposit' => number_format($query->sum('deposit')),
                        'withdraw' => number_format($query->sum('withdraw')),
                        'net' => number_format($query->sum('deposit') - $query->sum('withdraw'))
                    ];
                })

                ->addIndexColumn()

                ->addColumn('deposit', function ($q) {
                    return number_format($q->deposit) . " ($q->deposit_count) ";
                })

                ->addColumn('withdraw', function ($q) {
                    return number_format($q->withdraw) . " ($q->withdraw_count) ";
                })

                ->addColumn('net', function ($q) {
                    return number_format($q->net_amount);
                })

                ->addColumn('created_at', function ($data) {
                    return "<p class='text-center mb-0'>" . Carbon::parse($data->date)->format("d-m-Y") . "</p>";
                })

                ->filter(function ($instance) use ($request) {
                })

                ->rawColumns(['deposit', 'withdraw', 'net', 'created_at'])

                ->make(true);
        }

        return view("backend.report.agent-payments", compact('agents'));
    }

}
