<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('status', function ($user) {
                        if ($user->amount > 0) {
                            return '<label class="badge badge-success badge-pill">Active</label>';
                        } else {
                            return ' <label class="badge badge-warning badge-pill">Not Active</label>';
                        }
                    })
                    ->addColumn('days_not_logged_in', function ($user) {
                        return Carbon::parse(now())->diffInDays($user->last_active);
                    })
                    ->addColumn('payment', function ($user) {
                        if( is_admin() ){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$user->id.'" data-type="deposit"  data-original-title="Deposit" class="edit btn btn-info btn-sm payment"> + </a>';

                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$user->id.'" data-type="withdrawal" data-original-title="Withdrawal" class="btn btn-warning btn-sm payment"> - </a>';
                            return $btn;
                        }
                    })
                    ->addColumn('action', function ($user) {
                        if( is_admin() ){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$user->id.'" data-original-title="Edit" class="edit btn btn-warning editUser">Edit</a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$user->id.'" data-original-title="Delete" class="btn btn-danger deleteUser">Delete</a>';
                            return $btn;
                        }
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->orWhere('name', 'LIKE', "%$search%");
                                $w->orWhere('user_id', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['status','action','payment'])
                    ->make(true);
        }
        return view('backend.admin.users.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($request->old_id)) {
            $this->validate($request, [
                'name' => 'nullable|string|max:255',
                // 'phone' => 'required|phone:MM|unique:users',
                'user_id' => 'required|unique:users',
                'password' => 'required|string|min:4|same:confirm-password',
            ]);

            User::create([
                'name'     => $request->name,
                'user_id' => $request->user_id,
                // 'amount' => $request->amount,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
        } else {
            $this->validate($request, [
                'name' => 'nullable|string|max:255',
                // 'phone' => 'required|phone:MM|unique:users,phone,'.$request->user_id,
                'user_id' => 'required|unique:users,user_id,'.$request->old_id,
                'password' => 'nullable|string|min:4|same:confirm-password',
            ]);

            $user = User::find($request->old_id);
            $user->name = $request->name;
            $user->user_id = $request->user_id;
            $user->phone = $request->phone;
            // $user->amount = $request->amount;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        return response()->json(['success'=>'User saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'User deleted successfully.']);
    }
}
