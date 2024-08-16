<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Repository\BodyRecordRepository;
use App\Repository\MaungRecordRepository;

class BalloneRecordController extends Controller
{
    protected $filter, $type, $current_round;

    public function __construct(Request $request)
    {
        $this->filter = $request->only(['agent_id', 'round', 'start_date', 'end_date']);

    }

    public function index(Request $request, $type)
    {

        if ($request->ajax()) {

            $query = ($type == 'body')
                ? (new BodyRecordRepository($this->filter))->executeRecord()
                : (new MaungRecordRepository($this->filter))->executeRecord();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return getTotalAmountRecords($query);
                })

                ->addIndexColumn()

                ->addColumn('round', function ($q) {
                    return $q->round;
                })

                ->addColumn('betting_amount', function ($q) {
                    return number_format($q->betting_amount) . " ( $q->betting_count )";
                })

                ->addColumn('win_amount', function ($q) {
                    return number_format($q->win_amount) . " ( $q->win_count )";
                })

                ->addColumn('net_amount', function ($q) {
                    // return number_format($q->net_amount);

                    return number_format( $q->betting_amount - $q->win_amount);
                })

                ->addColumn('status', function ($q) {
                    return getNetAmountStatus($q->net_amount);
                })

                ->make(true);
        }

        return view("backend.record.ballone", compact('type'));
    }
}
