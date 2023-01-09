<?php

namespace App\Http\Controllers\Backend\Agent;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $referral_code = Auth::guard('agent')->user()->referral_code;
        if ($request->ajax()) {
            $query = User::where('referral_code', $referral_code)->select('*');
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('status', function ($user) {
                        if ($user->amount > 0) {
                            return '<label class="badge badge-success badge-pill">Active</label>';
                        } else {
                            return ' <label class="badge badge-warning badge-pill">Not Active</label>';
                        }
                    })
                    ->addColumn('created_at', function ($user) {
                        return date("F j, Y, g:i A", strtotime($user->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->orWhere('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['status'])
                    ->make(true);
        }
        return view('backend.agent.users.index');
    }

    public function store(Request $request)
    {
        if (is_null($request->user_id)) {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                // 'phone' => 'required|phone:MM|unique:users',
                'phone' => 'required|unique:users',
                'password' => 'required|string|min:4|same:confirm-password',
            ]);

            User::create([
                'name'     => $request->name,
                'phone' => $request->phone,
                'amount' => $request->amount,
                'password' => Hash::make($request->password),
                'referral_code' => Auth::user()->referral_code
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                // 'phone' => 'required|phone:MM|unique:users,phone,'.$request->user_id,
                'phone' => 'required|unique:users,phone,'.$request->user_id,
                'password' => 'nullable|string|min:4|same:confirm-password',
            ]);

            $user = User::find($request->user_id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->amount = $request->amount;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }
   
        return response()->json(['success'=>'User saved successfully.']);
    }
}
