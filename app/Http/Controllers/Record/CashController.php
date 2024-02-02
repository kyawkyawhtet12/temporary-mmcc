<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Cashout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CashController extends Controller
{
    protected $search;

    public function __construct(Request $request)
    {
        Session::put('search.agent_id', $request->agent_id ?? []);
        Session::put('search.user_id', $request->user_id ?? NULL);
        Session::put('search.name', $request->name ?? NULL);
        Session::put('search.phone', $request->phone ?? NULL );
        Session::put('search.start_date', $request->start_date ?? NULL);
        Session::put('search.end_date', $request->end_date ?? NULL);

        $this->search = Session::get('search');
    }

    public function index(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        $data = Cashout::with('user', 'admin')->latest();

        if( $agent_id = $this->search['agent_id'] ){
            $data = $data->whereIn("agent_id", $agent_id );
        }

        if ($user_id = $this->search['user_id']){
            $data = $data->whereHas('user', function ($query) use ($user_id) {
                         $query->where('user_id', 'like', $user_id.'%');
                    });
        }

        if ($name = $this->search['name']){
            $data = $data->whereHas('user', function ($query) use ($name) {
                        $query->where('name', 'like', $name.'%');
                    });
        }

        if ($phone = $this->search['phone']){
            // $data = $data->whereHas('user', function ($query) use ($phone) {
            //             $query->where('phone', 'like', $phone.'%');
            //         });

            $data = $data->where("phone", $phone);
        }

        if ($start_date = $this->search['start_date']){
            $data = $data->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date = $this->search['end_date']){
            $data = $data->whereDate('created_at', '<=', $end_date);
        }

        $data = $data->paginate(15);

        Session::put("search", $this->search);

        return view("backend.record.cash", compact('data','agents'));
    }

}
