<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use App\Models\BettingRecord;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\JoinClause;
use App\Http\Resources\BettingRecord\DetailResource;
use App\Models\TwoDigitTransaction;
use App\Repository\BalloneRecordRepository;
use App\Services\BettingRecord\DeleteService;

class BettingController extends Controller
{
    protected $ballone = false;

    public function __construct(protected BalloneRecordRepository $balloneRecord)
    {
        $this->ballone = in_array(request()->get('type'), ['Body', 'Maung']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $subquery = $this->ballone
                ? $this->balloneRecord->getSubQuery($request->type)
                : [];

            $query = BettingRecord::with('user')

                ->when($this->ballone, function ($q) use ($subquery) {
                    $q->joinSub($subquery, 'rounds', function (JoinClause $join) {
                        $join->on('betting_records.id', '=', 'rounds.betting_record_id');
                    });
                })
                ->latest();

            if($request->delete_status == 1){
                $query = $query->onlyTrashed();
            }


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

                    $view_btn = "
                            <a href='#record-details' class='btn btn-success btn-sm viewDetail' data-id='$q->id'>
                                View
                            </a>";

                    $delete_btn = $this->getDeleteButton($q);

                    return "$view_btn $delete_btn";

                })

                ->filter(function ($instance) use ($request) {

                    if ($search = $request->get('search')) {
                        $instance->whereHas('user', function ($w) use ($search) {
                            $w->where('name', 'LIKE', "%$search%");
                            $w->orWhere('user_id', 'LIKE', "%$search%");
                        });
                    }

                    if ($agent_id = $request->get('agent_id')) {
                        $instance->whereIn("agent_id", $agent_id);
                    }

                    if ($request->get('round') && $this->ballone) {
                        $instance->whereIn("round", request()->get('round'));
                    }

                    if ($user_id = $request->get("user_id")) {
                        $instance->whereHas('user', function ($w) use ($user_id) {
                            $w->where('user_id', $user_id);
                        });
                    }

                    if ($type = $request->get("type")) {
                        $instance->where('type', $type);
                    }

                    if ($min = $request->get('min_amount')) {
                        $instance->where('amount', '>=', $min);
                    }

                    if ($max = $request->get('max_amount')) {
                        $instance->where('amount', '<=', $max);
                    }

                    if ($start_date = $request->get('start_date')) {
                        $instance->whereDate('created_at', '>=', $start_date);
                    }

                    if ($end_date = $request->get('end_date')) {
                        $instance->whereDate('created_at', '<=', $end_date);
                    }
                })

                ->rawColumns(['actions'])

                ->make(true);
        }

        return view("backend.record.betting");
    }

    public function detail($id)
    {
        $data = BettingRecord::withTrashed()->findOrFail($id);
        return response()->json(new DetailResource($data));
    }

    protected function getDeleteButton($q)
    {
        $btn = (is_admin() && $q->result == 'No Prize' && in_array($q->type, ['2D', '3D']) && !$q->deleted_at )
        ? "
            <a href='#' class='btn btn-danger btn-sm ml-2 delete-record' data-route='/admin/betting-record/delete/$q->id'>
                Delete
            </a>"
        : "";

        return $btn;

    }
}
