<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Repository\WinRecordRepository;

class WinController extends Controller
{
    protected $search;

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = [
                'filter' => $request->only(['agent_id', 'user_id', 'start_date', 'end_date'])
            ];

            $query = (new WinRecordRepository($data))->execute();

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('user_id', function ($q) {
                    return $q->user_ids;
                })

                ->addColumn('amount', function ($q) {
                    return number_format($q->amount);
                })

                ->addColumn('time', function ($q) {
                return dateTimeFormat($q->created_at);
                    // return $q->created_at->format("d-m-Y g:i A");
                })

                ->filter(function ($instance) use ($request) {

                    // if ($search = $request->get('search')) {
                    //     $instance->whereHas('user', function ($w) use ($search) {
                    //         $w->where('name', 'LIKE', "%$search%");
                    //         $w->orWhere('user_id', 'LIKE', "%$search%");
                    //     });
                    // }

                    // if ($agent_id = $request->get('agent_id')) {
                    //     $instance->whereIn("agent_id", $agent_id);
                    // }

                    // if ($user_id = $request->get("user_id")) {
                    //     $instance->whereHas('user', function ($w) use ($user_id) {
                    //         $w->where('user_id', $user_id);
                    //     });
                    // }

                    // if ($start_date = $request->get('start_date')) {
                    //     $instance->whereDate('date', '>=', $start_date);
                    // }

                    // if ($end_date = $request->get('end_date')) {
                    //     $instance->whereDate('date', '<=', $end_date);
                    // }
                })

                ->make(true);
        }

        return view("backend.record.win");
    }
}
