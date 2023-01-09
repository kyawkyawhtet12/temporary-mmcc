<?php

namespace App\Http\Controllers\Backend\Agent;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\AgentWithdraw;
use App\Models\PaymentProvider;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $providers = PaymentProvider::select('name', 'id')->get();
        return view("backend.agent.withdrawal.index", compact('providers'));
    }

    public function store(Request $request)
    {
        // return $request->all();

        $request->validate([
            'payment_provider_id' => ['required'],
            'amount' => ['required'],
            'phone' => ['required','numeric','min:9'],
            'remark' => ['required'],
        ]);

        if ($request->amount > Auth::user()->amount) {
            return back()->with('fail', 'Invalid Amount');
        }
        
        AgentWithdraw::create([
            'payment_provider_id' => $request->payment_provider_id,
            'amount' => $request->amount,
            'phone' => $request->phone,
            'remark' => $request->remark,
            'agent_id' => Auth::id(),
        ]);
        // $agent = Agent::find(Auth::guard('agent')->user()->id);
        Agent::find(Auth::id())->decrement('amount', $request->amount);
        return back()->with('success', 'Withdraws form send successfully');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentWithdraw::with('agent', 'provider')->where('agent_id', Auth::id())->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($cashout) {
                        return '<label class="badge badge-info badge-pill">'.$cashout->provider->name.'</label>';
                    })
                    ->addColumn('agent', function ($cashout) {
                        return '<label class="badge badge-info badge-pill">'.$cashout->agent->name.'</label>';
                    })
                    ->addColumn('amount', function ($cashout) {
                        return '<label class="badge badge-primary badge-pill">'.$cashout->amount.' MMK</label>';
                    })
                    ->addColumn('status', function ($cashout) {
                        return  '<label class="badge badge-success badge-pill">'.$cashout->status.'</label>';
                    })
                    ->addColumn('created_at', function ($cashout) {
                        return date("F j, Y, g:i A", strtotime($cashout->created_at));
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
        return view('backend.agent.withdrawal.history');
    }
}
