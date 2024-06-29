<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repository\BalloneRecordRepository;

class BalloneRecordController extends Controller
{
    protected $filter, $type, $current_round;

    public function __construct(Request $request)
    {
        $this->filter = $request->only(['agent_id', 'round', 'start_date', 'end_date']);

        $this->current_round = DB::table('football_matches')->latest()->first()?->round;
    }

    public function index(Request $request, $type)
    {
        $current_round = $this->current_round;

        if ($request->ajax()) {

            $query = ($type == 'body')
                ? (new BalloneRecordRepository($this->filter))->getBodyRecord()
                : (new BalloneRecordRepository($this->filter))->getMaungRecord();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return getTotalAmountRecords($query);
                })

                ->addIndexColumn()

                ->addColumn('round', function ($q) {
                    return $q->round;
                })

                ->addColumn('betting_amount', function ($q) {
                    return number_format($q->betting_amount);
                })

                ->addColumn('win_amount', function ($q) {
                    return number_format($q->win_amount);
                })

                ->addColumn('net_amount', function ($q) {
                    return number_format($q->net_amount);
                })

                ->addColumn('status', function ($q) {
                    return getNetAmountStatus($q->net_amount);
                })

                ->make(true);
        }

        return view("backend.record.ballone", compact('current_round', 'type'));
    }
}
