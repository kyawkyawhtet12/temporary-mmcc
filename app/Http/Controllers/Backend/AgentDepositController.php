<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentDeposit;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class AgentDepositController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentDeposit::with('provider', 'agent')->where('status', 0)->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($payment) {
                        return '<span>'.$payment->provider->name.'</span>';
                    })
                    ->addColumn('agent', function ($payment) {
                        return '<span>'.$payment->agent->name.'</span>';
                    })
                    ->addColumn('amount', function ($payment) {
                        return '<span>'.$payment->amount.' MMK</span>';
                    })
                    ->addColumn('status', function ($payment) {
                        if ($payment->status == 0) {
                            return 'Pending';
                        } elseif ($payment->status == 1) {
                            return 'Approved';
                        } else {
                            return 'Rejected';
                        }
                    })
                    ->addColumn('transaction', function ($payment) {
                        if ($payment->transaction) {
                            return "<a href='javascript:void(0)' data-toggle='tooltip'  data-img='$payment->transaction' id='imgClick'>
                                <img src='$payment->transaction'>
                            </a>";
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('created_at', function ($payment) {
                        return date("d-m-Y, g:i A", strtotime($payment->created_at));
                    })
                    ->addColumn('actions', function ($payment) {
                        return "
                            <a href='#' data-route='/admin/agent-deposit/accept/$payment->id' data-type='accept' class='btn btn-success btn-sm'> Accept </a>

                            <a href='#' data-route='/admin/agent-deposit/reject/$payment->id' data-type='reject' class='btn btn-danger btn-sm'> Reject </a>

                        ";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('provider', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('agent', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['provider','agent','amount','status','transaction','actions'])
                    ->make(true);
        }
        return view('backend.admin.agent-deposits.index');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentDeposit::with('provider', 'agent')->where('status', '!=', 0)->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($payment) {
                        return '<span>'.$payment->provider->name.'</span>';
                    })
                    ->addColumn('agent', function ($payment) {
                        return '<span>'.$payment->agent->name.'</span>';
                    })
                    ->addColumn('amount', function ($payment) {
                        return '<span>'.$payment->amount.' MMK</span>';
                    })
                    ->addColumn('status', function ($payment) {
                        if ($payment->status == 0) {
                            return 'Pending';
                        } elseif ($payment->status == 1) {
                            return 'Approved';
                        } else {
                            return 'Rejected';
                        }
                    })
                    ->addColumn('transaction', function ($payment) {
                        if ($payment->transaction) {
                            return "<a href='javascript:void(0)' data-toggle='tooltip'  data-img='$payment->transaction' id='imgClick'>
                                <img src='$payment->transaction'>
                            </a>";
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('created_at', function ($payment) {
                        return date("d-m-Y, g:i A", strtotime($payment->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('provider', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('agent', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['provider','agent','amount','status','transaction'])
                    ->make(true);
        }
        return view('backend.admin.agent-deposits.history');
    }

    public function accept($id)
    {
        $data = AgentDeposit::find($id);
        if (!$data) {
            return 'error';
        }

        $data->update(['status' => 1]);
        Agent::find($data->agent_id)->increment('amount', $data->amount);
        return back()->with('success', 'Deposit accepted successfully');
    }

    public function reject($id)
    {
        $data = AgentDeposit::find($id);
        if (!$data) {
            return 'error';
        }

        $data->update(['status' => 2]);
        return back()->with('success', 'Deposit rejected successfully');
    }
}
