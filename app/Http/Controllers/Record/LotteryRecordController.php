<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repository\LotteryRecordRepository;

class LotteryRecordController extends Controller
{
    protected $results , $data;

    public function __construct(Request $request)
    {
        $this->data = [
            'filter' => $request->only(['agent_id','start_date','end_date']),
            'type' => $request->type
        ];

        $this->results = ( $request->type == '3D') ? DB::table("three_lucky_numbers")->pluck("updated_at", "date_id") : [];

        abort_if(!in_array($request->type, ['2D', '3D']) , 404);
    }

    public function index(Request $request, $type)
    {
        if ($request->ajax()) {

        $query = ( new LotteryRecordRepository($this->data))->getQueryCollections();

        return Datatables::of($query)

            ->with('total', function () use ($query) {
                return getTotalAmountRecords($query);
            })

            ->addIndexColumn()

            ->addColumn('date', function ($q) {
                return dateFormat(($this->data['type'] == '2D') ? $q->date : $this->results[$q->round]);
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

        return view("backend.record.lottery", compact('type'));
    }

}
