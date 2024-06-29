<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use App\Models\BettingRecord;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Http\Resources\BettingRecord\DetailResource;

class BettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = BettingRecord::with('user')->latest();

            return Datatables::of($query)

                    ->addIndexColumn()

                    ->addColumn('user_id', function ($q) {
                        return $q->user->user_id;
                    })

                    ->addColumn('amount', function ($q) {
                        return number_format($q->amount);
                    })

                    ->addColumn('time', function ($q) {
                        return $q->created_at->format("d-m-Y g:i A");
                    })

                    ->addColumn('results', function ($q) {
                        return $q->result ?? "No Prize";
                    })

                    ->addColumn('wins', function ($q) {
                        return number_format($q->win_amount ?? 0);
                    })

                    ->addColumn('actions', function ($q) {
                        return "
                            <a href='#record-details' class='btn btn-success btn-sm viewDetail' data-id='$q->id'>
                                View
                            </a>
                        ";
                    })

                    ->filter(function ($instance) use ($request) {

                        if ( $search = $request->get('search') ) {
                            $instance->whereHas('user', function ($w) use ($search) {
                                    $w->where('name', 'LIKE', "%$search%");
                                    $w->orWhere('user_id', 'LIKE', "%$search%");
                            });
                        }

                        if( $agent_id = $request->get('agent_id') ){
                            $instance->whereIn("agent_id", $agent_id );
                        }

                        if( $user_id = $request->get("user_id")){
                            $instance->whereHas('user', function ($w) use ($user_id) {
                                $w->where('user_id', $user_id);
                            });
                        }

                        if( $type = $request->get("type")){
                            $instance->where('type', $type);
                        }

                        if ( $min = $request->get('min_amount') ){
                            $instance->where('amount', '>=' , $min);
                        }

                        if ( $max = $request->get('max_amount') ){
                            $instance->where('amount', '<=' , $max);
                        }

                        if ($start_date = $request->get('start_date')){
                            $instance->whereDate('created_at', '>=', $start_date);
                        }

                        if ($end_date = $request->get('end_date')){
                            $instance->whereDate('created_at', '<=', $end_date);
                        }

                    })

                    ->rawColumns([ 'actions' ])

                    ->make(true);
        }

        return view("backend.record.betting");
    }

    public function detail($id)
    {
        $data = BettingRecord::findOrFail($id);
        return response()->json(new DetailResource($data));
    }
}
