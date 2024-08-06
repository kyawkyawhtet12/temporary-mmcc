<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use App\Models\BettingRecord;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Services\BettingRecord\DeleteService;

class DeleteRecordController extends Controller
{
    public function __construct()
    {
        // dd(request()->user());
        // if(is_admin()){
        //     return back()->with('error', 'error');
        // }
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = BettingRecord::with('user')->onlyTrashed()->latest('deleted_at');

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('user_id', function ($q) {
                    return $q->user->user_id;
                })

                ->addColumn('amount', function ($q) {
                    return number_format($q->amount);
                })

                ->addColumn('time', function ($q) {
                    return $q->created_at->format("d-m-Y h:i A");
                })

                ->addColumn('deleted_at', function ($q) {
                    return $q->deleted_at->format("d-m-Y h:i A");
                })

                ->filter(function ($instance) use ($request) {

                    if ($agent_id = $request->get('agent_id')) {
                        $instance->whereIn("agent_id", $agent_id);
                    }

                    if ($user_id = $request->get("user_id")) {
                        $instance->whereHas('user', function ($w) use ($user_id) {
                            $w->where('user_id', $user_id);
                        });
                    }

                    if ($type = $request->get("type")) {
                        $instance->where('type', $type);
                    }

                    if ($start_date = $request->get('start_date')) {
                        $instance->whereDate('created_at', '>=', $start_date);
                    }

                    if ($end_date = $request->get('end_date')) {
                        $instance->whereDate('created_at', '<=', $end_date);
                    }
                })

                ->make(true);
        }

        return view("backend.record.delete_record_history");
    }

    public function delete($id)
    {
        if( !is_admin() )
        {
            return back()->with('error', 'error');
        }

        $data = BettingRecord::findOrFail($id);

        try {

            (new DeleteService($data))->execute();

            return back()->with('success', 'success');

        } catch (\Throwable $th) {

            return back()->with('error', 'error');

        }

    }
}
