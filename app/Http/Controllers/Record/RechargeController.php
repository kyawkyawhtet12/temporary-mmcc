<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RechargeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Payment::with('user.cashoutPhone', 'admin', 'agent', 'provider')
                ->filterAgent()
                ->filterUser()
                ->filterPhone()
                ->filterByDate()
                ->latest();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return $query->whereStatus("Approved")->sum('amount');
                })

                ->addIndexColumn()

                ->addColumn('user_id', function ($q) {
                    return $q->user->user_id;
                })

                // ->addColumn('amount', function ($q) {
                //     return number_format($q->amount);
                // })

                ->addColumn('amount', function ($q) {
                    return "<span class='amountValue' data-amount='{$q->amount}'>" . number_format($q->amount) . "</span>";
                })

                ->addColumn('provider_name', function ($q) {
                    return $q->provider_name;
                })

                ->addColumn('phone', function ($payment) {

                    if( $payment->phone ){
                        return $payment->phone;
                    }

                    if( $cashoutPhone = $payment->user->cashoutPhone ){

                       $kpay = ($cashoutPhone->kpay) ? "<span>$cashoutPhone->kpay (kpay) </span>" : "";
                       $wave = ($cashoutPhone->wave) ? "<span>$cashoutPhone->wave (wave) </span>" : "";

                       return ($cashoutPhone->kpay == $cashoutPhone->wave)
                            ? "$cashoutPhone->kpay"
                            : "$kpay <br><br> $wave";
                    }

                    return '';

                })
                ->addColumn('created_at', function ($q) {
                    return $q->created_at->format("d-m-Y g:i A");
                })

                ->addColumn('action_time', function ($q) {
                    return $q->action_time;
                })

                ->addColumn('process_time', function ($q) {
                    return $q->process_time;
                })

                ->addColumn('actions', function ($q) {
                    if ($q->status == 'Approved') {

                        if (is_admin()) {
                            return "
                            <a href='#' class='btn btn-danger btn-sm deleteBtn' data-id='$q->id'>
                                Delete
                            </a>
                        ";
                        }
                    }

                    return "";
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

                ->rawColumns(['actions', 'amount', 'phone'])

                ->make(true);
        }

        return view("backend.record.recharge");
    }
}
