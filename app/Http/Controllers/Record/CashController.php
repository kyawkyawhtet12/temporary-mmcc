<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use App\Models\Cashout;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Services\Daily\PaymentRejectService;

class CashController extends Controller
{
    public function __construct(protected PaymentRejectService $rejectService)
    {

    }

    public function index(Request $request)
    {
        $this->rejectService->executeWithdrawal();

        if ($request->ajax()) {

            $query = Cashout::with('user', 'admin')
                ->filterAgent()
                ->filterUser()
                ->filterPhone()
                ->filterByDate()
                ->latest();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return $query->clone()->whereStatus("Approved")->sum('amount');
                })

                ->addIndexColumn()

                ->addColumn('user_id', function ($q) {
                    return $q->user->user_id;
                })

                ->addColumn('amount', function ($q) {
                    return number_format($q->amount);
                })

                ->addColumn('provider_name', function ($q) {
                    return $q->provider_name;
                })

                ->addColumn('created_at', function ($q) {
                    return $q->created_at->format("d-m-Y h:i A");
                })

                ->addColumn('action_time', function ($q) {
                    return $q->action_time;
                })

                ->addColumn('process_time', function ($q) {
                    return $q->process_time;
                })

                ->filter(function ($instance) use ($request) {

                    // if ($search = $request->get('search')) {
                    //     $instance->whereHas('user', function ($w) use ($search) {
                    //         $w->where('name', 'LIKE', "%$search%");
                    //         $w->orWhere('user_id', 'LIKE', "%$search%");
                    //     });
                    // }

                    // if ($agent_id = $request->get('agent_id')) {
                    //     $instance->whereIn("agent_id", $agent_id);
                    // }

                    // if ($user_id = $request->get("user_id")) {
                    //     $instance->whereHas('user', function ($w) use ($user_id) {
                    //         $w->where('user_id', $user_id);
                    //     });
                    // }

                    // if ($phone = $request->get("phone")) {
                    //     $instance->where('phone', $phone);
                    // }

                    // if ($start_date = $request->get('start_date')) {
                    //     $instance->whereDate('created_at', '>=', $start_date);
                    // }

                    // if ($end_date = $request->get('end_date')) {
                    //     $instance->whereDate('created_at', '<=', $end_date);
                    // }
                })

                ->make(true);
        }

        return view("backend.record.cash");
    }
}
