<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\User;
use App\Models\Enabled;
use App\Models\AgentWithdraw;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $total_amount = Agent::sum('amount');
        $total_agent = Agent::count();
        $total_user = User::count();

        $agent_withdraw = AgentWithdraw::where([
                                                ['status', '=', 'Completed'],
                                                ['created_at', '>=', Carbon::today()],
                                            ])->sum('amount');
       
        $enabled = Enabled::first();
        $two_digits = TwoDigit::all();
        
        return view('backend.admin.index', compact(
            'two_digits',
            'enabled',
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

    public function changeTwoDigitEnable(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => null
        ]);
        return response()->json('success');
    }

    public function changeTwoDigitDisable(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => $request->date
        ]);
        return response()->json('success');
    }

    public function changeTwoDigitSubmit(Request $request)
    {
        TwoDigit::whereIn('id', explode(",", $request->ids))->update([
            'amount' => $request->amount,
            'date' => $request->date
        ]);
        return response()->json('success');
    }
}
