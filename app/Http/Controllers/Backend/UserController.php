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

            $query = User::latest('id');

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
                        return now()->diffInDays($user->last_active);
                    })
                    ->addColumn('payment', function ($user) {
                        if( is_admin() ){
                            return "
                                <a href='javascript:void(0)' class='btn btn-info btn-sm paymentBtn' data-id='{$user->id}' data-type='recharge' data-title='Recharge For {$user->user_id}'>
                                    +
                                </a>

                                <a href='javascript:void(0)' class='btn btn-warning btn-sm paymentBtn' data-id='{$user->id}' data-type='cashout' data-title='Cashout For {$user->user_id}'>
                                    -
                                </a>
                            ";
                        }
                    })
                    ->addColumn('action', function ($user) {
                        if( is_admin() ){
                            return "
                                <a href='javascript:void(0)' class='edit btn btn-info btn-sm editUser' data-id='{$user->id}' data-name='{$user->name}' data-user_id='{$user->user_id}'>
                                    Edit
                                </a>

                                <a href='javascript:void(0)' class='btn btn-danger btn-sm deleteUser' data-id='{$user->id}' data-name='{$user->name}' data-user_id='{$user->user_id}'>
                                    Delete
                                </a>
                            ";
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
        $this->validate($request, [
            'name' => 'nullable|string|max:255',
            // 'phone' => 'required|phone:MM|unique:users,phone,'.$request->user_id,
            'user_id' => 'required|unique:users,user_id,'.$request->old_id,
            'password' => 'nullable|string|min:7',
            'old_id' => 'required',
        ]);

        $user = User::find($request->old_id);

        if(!$user){
            return response()->json(['error' => '* User not found.']);
        }

        $user->name = $request->name;
        $user->user_id = $request->user_id;
        $user->phone = $request->phone;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['success' => '* Successfully updated.']);
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
