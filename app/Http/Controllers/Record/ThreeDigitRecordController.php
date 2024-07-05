<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Repository\ThreeDigitRecordRepository;

class ThreeDigitRecordController extends Controller
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

            $query = (new ThreeDigitRecordRepository($this->data))->execute();

            return Datatables::of($query)

                ->with('total', function () use ($query) {
                    return getTotalAmountRecords($query->clone()->get());
                })

                ->addIndexColumn()

                ->addColumn('date', function ($q) {
                    return dateFormat($q->result_date);
                })

                ->addColumn('time', function ($q) {
                    return "";
                })

                ->addColumn('result_number', function ($q) {
                    return $q->number;
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

                ->rawColumns(['time', 'betting_amount', 'win_amount', 'net_amount', 'status', 'result_number'])

                ->make(true);
        }

        return view("backend.record.lottery", ['type' => '3D' ]);
    }
}
