<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\WinRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WinController extends Controller
{

    public function search_session_clear()
    {
        Session::forget(['user_id', 'name', 'phone','start_date','end_date']);
    }

    public function search_session_put($request)
    {
        Session::put('user_id', $request->user_id);
        Session::put('name', $request->name);
        Session::put('phone', $request->phone);
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

        $data = WinRecord::with('user')->latest();

        if($select_agent && $select_agent != 'all'){
            $data = $data->where('agent_id', $select_agent);
        }

        $data = $this->getData($request, $data);

        return view("backend.record.win", compact('data','agents','select_agent'));
    }

    public function search(Request $request)
    {
        $agents = Agent::select('id','name')->get();

        Session::put('agent', 'all');
        $select_agent = Session::get('agent');

        $data = WinRecord::with('user')->latest();

        $this->search_session_put($request);

        $data = $this->getData($request, $data);

        return view("backend.record.win", compact('data','agents','select_agent'));
    }

    public function getData($request, $data)
    {
        $user_id = Session::get('user_id');
        $name = Session::get('name');
        $phone = Session::get('phone');
        $start_date = Session::get('start_date');
        $end_date = Session::get('end_date');

        if (!empty($user_id)){
            $data = $data->whereHas('user', function ($query) use ($user_id) {
                        $query->where('user_id', 'like', $user_id.'%');
                    });
        }

        if (!empty($name)){
            $data = $data->whereHas('user', function ($query) use ($name) {
                        $query->where('name', 'like', $name.'%');
                    });
        }

        if (!empty($phone)){
            $data = $data->whereHas('user', function ($query) use ($phone) {
                        $query->where('phone', 'like', $phone.'%');
                    });
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
