<?php

namespace App\Http\Controllers\Testing;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLogFixController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);

        return view("backend.admin.users.user_log", compact("user"));
    }

    public function add(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $end_balance = ($request->type === 'increment')
            ? $user->amount + $request->amount
            : $user->amount - $request->amount;

        UserLog::create([
            'agent_id' => $user->agent->id,
            'user_id' => $user->id,
            'remark' => $request->remark,
            'operation' => $request->operation,
            'amount' => $request->amount,
            'start_balance' => $user->amount,
            'end_balance' => $end_balance
        ]);


        if ($request->type === 'increment') {
            $user->increment("amount", $request->amount);
        }

        if ($request->type === 'decrement') {
            $user->decrement("amount", $request->amount);
        }

        return back()->with('success', 'success');
    }

    // check amount
    public function check_amount()
    {
        $date = today();
        // $date = '2024-08-24';

        $users = User::with('last_log:id,user_id,end_balance')->whereDate('updated_at', $date)->get();

        // return $users;
        return view("backend.admin.users.user_log_check", compact("users"));
    }

}
