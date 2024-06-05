<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    protected $search;

    public function __construct(Request $request)
    {
        Session::put('search.agent_id', $request->agent_id ?? []);

        $this->search = Session::get('search');
    }

    public function index(Request $request)
    {

        $agents = Agent::select('id','name','referral_code')->get();

        if ($request->ajax()) {

            $query = User::withCount('approved_cashouts')->withCount('approved_deposits')->with('cashoutPhone')->latest('id');

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
                    ->addColumn('kpay', function($user){

                        if( $user->cashoutPhone){
                            return "{$user->cashoutPhone->kpay}";
                        }

                        return " ";
                    })
                    ->addColumn('wave', function($user){

                        if( $user->cashoutPhone){
                            return "{$user->cashoutPhone->wave}";
                        }

                        return " ";
                    })
                    ->addColumn('amount', function($user){
                        return number_format($user->amount);
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
                    ->addColumn('amount_details', function($user){
                        return "
                            <a href='/admin/amount-details/{$user->id}' class='btn btn-success'>
                                View
                            </a>
                        ";
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

                        if($request->get('agent_id')){
                            $instance->whereIn('referral_code', $request->agent_id);
                        }

                        if ( $search = $request->get('search')) {
                            $instance->where(function ($w) use ($search) {
                                $w->orWhere('name', 'LIKE', "%$search%");
                                $w->orWhere('user_id', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('cashoutPhone', function ($w) use ($search) {
                                $w->where('kpay', 'LIKE', "%$search%");
                                $w->orWhere('wave', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['status','action','payment','amount_details'])
                    ->make(true);
        }
        return view('backend.admin.users.index', compact("agents"));
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
            'password' => 'nullable|string|min:5',
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
