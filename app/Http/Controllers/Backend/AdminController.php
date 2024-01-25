<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\AgentPaymentAllReport;
use App\Models\TwoDigit;
use App\Models\User;
use App\Models\Enabled;
use App\Models\AgentWithdraw;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {

        $payment = AgentPaymentAllReport::query();

        $total_amount = number_format($payment->sum('deposit') - $payment->sum('withdraw'));

        $total_agent = Agent::count();
        $total_user = User::count();

        $agent_withdraw = AgentWithdraw::where([
                                                ['status', '=', 'Completed'],
                                                ['created_at', '>=', Carbon::today()],
                                            ])->sum('amount');

        return view('backend.admin.index', compact(
            'total_amount',
            'total_agent',
            'agent_withdraw',
            'total_user'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'string', 'min:8'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        if (Auth::guard('admin')->check()) {
            Admin::find(Auth::guard('admin')->user()->id)
                ->update(['password'=> Hash::make($request->new_password)]);
        } else {
            Agent::find(Auth::guard('agent')->user()->id)
                ->update(['password'=> Hash::make($request->new_password)]);
        }
        return back()->with('success', 'Password change successfully.');
    }


}
