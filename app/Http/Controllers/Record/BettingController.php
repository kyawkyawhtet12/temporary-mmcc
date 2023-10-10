<?php

namespace App\Http\Controllers\Record;

use App\Models\Agent;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\BettingRecord;
use App\Models\ThreeLuckyDraw;
use App\Models\ThreeLuckyNumber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\BettingRecord\DetailResource;
use App\Http\Resources\BettingRecord\BodyDetailResource;
use App\Models\FootballBet;

class BettingController extends Controller
{

    public function search_session_clear()
    {
        Session::forget(['user_id','type', 'min', 'max','start_date','end_date']);
    }

    public function search_session_put($request)
    {
        Session::put('user_id', $request->user_id);
        Session::put('type', $request->type);
        Session::put('min', $request->min);
        Session::put('max', $request->max);
        Session::put('start_date', $request->start_date);
        Session::put('end_date', $request->end_date);
    }

    public function index(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        if($request->agent){
            Session::put('agent', $request->agent);
            $this->search_session_clear();
        }

        if($request->agent == 'all' || $request->reset == 1){
            Session::put('agent', 'all');
            $this->search_session_clear();
        }

        $select_agent = Session::get('agent');

        $data = BettingRecord::with('user')->latest();

        if($select_agent && $select_agent != 'all'){
            $data = $data->where('agent_id', $select_agent);
        }

        $data = $this->getData($request, $data);
        $select_type = 'all';

        return view("backend.record.betting", compact('data','agents','select_agent','select_type'));
    }

    public function detail($id)
    {
        $data = BettingRecord::findOrFail($id);
        return response()->json(new DetailResource($data));
    }

    public function search(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        Session::put('agent', 'all');
        $select_agent = Session::get('agent');

        $data = BettingRecord::with('user')->latest();

        $this->search_session_put($request);

        $data = $this->getData($request, $data);
        $select_type = $request->type;

        return view("backend.record.betting", compact('data','agents','select_agent','select_type'));
    }

    public function getData($request, $data)
    {
        $user_id = Session::get('user_id');
        $min = Session::get('min');
        $max = Session::get('max');
        $type = Session::get('type');
        $start_date = Session::get('start_date');
        $end_date = Session::get('end_date');

        if (!empty($user_id)){
            $data = $data->whereHas('user', function ($query) use ($user_id) {
                        $query->where('user_id', 'like', $user_id.'%');
                    });
        }

        if($type && $type != 'all'){
            $data = $data->where('type', $type);
        }

        if (!empty($min)){
            $data = $data->where('amount', '>=' , $min);
        }

        if (!empty($max)){
            $data = $data->where('amount', '<=' , $max);
        }

        if (!empty($start_date)){
            $data = $data->whereDate('created_at', '>=', $start_date);
        }

        if (!empty($end_date)){
            $data = $data->whereDate('created_at', '<=', $end_date);
        }

        $data = $data->paginate(15);

        return $data;
    }
}
