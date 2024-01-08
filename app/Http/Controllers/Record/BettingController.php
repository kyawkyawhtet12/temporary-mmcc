<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\BettingRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\BettingRecord\DetailResource;

class BettingController extends Controller
{
    protected $search;

    public function __construct(Request $request)
    {
        Session::put('search.agent_id', $request->agent_id ?? []);
        Session::put('search.user_id', $request->user_id ?? NULL);
        Session::put('search.type', $request->type ?? NULL);
        Session::put('search.min', $request->min ?? NULL );
        Session::put('search.max', $request->max ?? NULL );
        Session::put('search.start_date', $request->start_date ?? NULL);
        Session::put('search.end_date', $request->end_date ?? NULL);

        $this->search = Session::get('search');
    }

    public function index(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        $data = BettingRecord::with('user')->latest();

        if( $agent_id = $this->search['agent_id'] ){
            $data = $data->whereIn("agent_id", $agent_id );
        }

        if ($user_id = $this->search['user_id']){
            $data = $data->whereHas('user', function ($query) use ($user_id) {
                         $query->where('user_id', 'like', $user_id.'%');
                    });
        }

        if ( $type = $this->search['type'] ){
            $data = $data->where('type', $type);
        }

        if ( $min = $this->search['min'] ){
            $data = $data->where('amount', '>=' , $min);
        }

        if ( $max = $this->search['max'] ){
            $data = $data->where('amount', '<=' , $max);
        }

        if ($start_date = $this->search['start_date']){
            $data = $data->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date = $this->search['end_date']){
            $data = $data->whereDate('created_at', '<=', $end_date);
        }

        $data = $data->paginate(15);

        Session::put("search", $this->search);

        return view("backend.record.betting", compact('data','agents' ));
    }

    public function detail($id)
    {
        $data = BettingRecord::findOrFail($id);
        return response()->json(new DetailResource($data));
    }
}
