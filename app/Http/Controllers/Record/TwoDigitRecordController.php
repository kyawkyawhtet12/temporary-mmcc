<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Repository\TwoDigitRecordRepository;

class TwoDigitRecordController extends Controller
{
    protected $data;

    public function __construct(Request $request)
    {
        $this->data = [
            'filter' => $request->only(['agent_id', 'start_date', 'end_date'])
        ];
    }

    public function index(Request $request)
    {
       if ($request->ajax()) {

            $query = (new TwoDigitRecordRepository($this->data))->execute();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return [
                        'betting' => $query->sum('betting_amount'),
                        'win'     => $query->sum('win_amount')
                    ];
                })

                ->addIndexColumn()

                ->addColumn('date', function ($q) {
                    return dateFormat($q->date);
                })

                ->addColumn('time', function ($q) {
                    return ($q->time == 1) ? "12:00 PM" : "4:30 PM";
                })

                ->addColumn('result_number', function ($q) {
                    return $q->result;
                })

                ->addColumn('betting_amount', function ($q) {
                    return number_format($q->betting_amount)  . " ( $q->betting_count )";
                })

                ->addColumn('win_amount', function ($q) {
                    return number_format($q->win_amount) . " ($q->win_count)";
                })

                ->addColumn('net_amount', function ($q) {
                    return number_format($q->betting_amount - $q->win_amount);
                })

                ->addColumn('status', function ($q) {
                    return getNetAmountStatus($q->betting_amount - $q->win_amount);
                })

                ->rawColumns(['time', 'betting_amount', 'win_amount', 'net_amount', 'status', 'result_number'])

                ->make(true);
        }

        return view("backend.record.lottery", ['type' => '2D' ]);
    }
}
