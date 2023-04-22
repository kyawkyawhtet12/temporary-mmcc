<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use App\Rules\LimitWithdraw;
use App\Models\AgentWithdraw;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;

class AgentWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentWithdraw::with('provider', 'agent')->whereStatus('0')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($cashout) {
                        return '<span>'.$cashout->provider->name.'</span>';
                    })
                    ->addColumn('agent', function ($cashout) {
                        return '<span>'.$cashout->agent->name.'</span>';
                    })
                    ->addColumn('amount', function ($cashout) {
                        return '<span>'.$cashout->amount.' MMK</span>';
                    })
                    ->addColumn('status', function ($cashout) {
                        if ($cashout->status == 0) {
                            return 'Pending';
                        } elseif ($cashout->status == 1) {
                            return 'Approved';
                        } else {
                            return 'Rejected';
                        }
                    })
                    ->addColumn('created_at', function ($cashout) {
                        return date("d-m-Y, g:i A", strtotime($cashout->created_at));
                    })
                    ->addColumn('actions', function ($cashout) {
                        return "
                            <a href='#' data-route='/admin/agent-withdrawal/accept/$cashout->id' data-type='accept' class='btn btn-success btn-sm'> Accept </a>

                            <a href='#' data-route='/admin/agent-withdrawal/reject/$cashout->id' data-type='reject' class='btn btn-danger btn-sm'> Reject </a>

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
                    ->rawColumns(['provider','agent','amount','status','actions'])
                    ->make(true);
        }
        return view('backend.admin.agent-withdraws.index');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentWithdraw::with('provider', 'agent')->where('status', '!=', 0)->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($cashout) {
                        return '<span>'.$cashout->provider->name.'</span>';
                    })
                    ->addColumn('agent', function ($cashout) {
                        return '<span>'.$cashout->agent->name.'</span>';
                    })
                    ->addColumn('amount', function ($cashout) {
                        return '<span>'.$cashout->amount.' MMK</span>';
                    })
                    ->addColumn('status', function ($cashout) {
                        if ($cashout->status == 0) {
                            return 'Pending';
                        } elseif ($cashout->status == 1) {
                            return 'Approved';
                        } else {
                            return 'Rejected';
                        }
                    })
                    ->addColumn('created_at', function ($cashout) {
                        return date("d-m-Y, g:i A", strtotime($cashout->created_at));
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
                    ->rawColumns(['provider','agent','amount','status'])
                    ->make(true);
        }
        return view('backend.admin.agent-withdraws.history');
    }

    // public function store(Request $request)
    // {
    //     $validateData = $request->validate([
    //         'payment_provider_id' => ['required'],
    //         'amount' => ['required', new LimitWithdraw],
    //         'phone' => ['required', 'phone:MM'],
    //         'remark' => ['required'],
    //     ]);
    //     $validateData['agent_id'] = Auth::guard('agent')->user()->id;
    //     AgentWithdraw::create($validateData);
    //     $agent = Agent::find(Auth::guard('agent')->user()->id);
    //     Agent::find($agent->id)->update(['amount'=> $agent->amount - $validateData['amount']]);
    //     return redirect()->route('agent.withdraw')->with('success', 'Withdraws form send successfully');
    // }

    // public function changeTransferStatus(Request $request)
    // {
    //     $cashout = AgentWithdraw::find($request->cashout_id);
    //     $cashout->status = $request->status;
    //     $cashout->save();
    //     return response()->json(['message' => 'Withdraws status changed successfully.']);
    // }

    public function accept($id)
    {
        $data = AgentWithdraw::find($id);
        if (!$data) {
            return 'error';
        }
        // Agent::find($data->agent_id)->decrement('amount', $data->amount);
        $data->update(['status' => 1]);
        return back()->with('success', 'Withdrawal accepted successfully');
    }

    public function reject($id)
    {
        $data = AgentWithdraw::find($id);
        if (!$data) {
            return 'error';
        }
        $data->update(['status' => 2]);
        Agent::find($data->agent_id)->increment('amount', $data->amount);
        return back()->with('success', 'Withdrawal rejected successfully');
    }
}
