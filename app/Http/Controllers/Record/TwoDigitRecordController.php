<?php

namespace App\Http\Controllers\Record;

use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\BettingRecord;
use App\Models\ThreeLuckyDraw;
use App\Models\TwoLuckyNumber;
use App\Models\ThreeLuckyNumber;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\JoinClause;
use App\Repository\LotteryResultRepository;
use App\Repository\TwoDigitRecordRepository;
use App\Repository\ThreeDigitRecordRepository;

class TwoDigitRecordController extends Controller
{
    protected $data;
    protected $results;

    public function __construct(Request $request, protected LotteryResultRepository $lotteryResultRepository)
    {
        $this->data = [
            'filter' => $request->only(['agent_id', 'start_date', 'end_date'])
        ];

        $this->results = Cache::remember('results', 60 * 5 , function(){
            return $this->lotteryResultRepository->executeResults('2D');
        });
    }

    public function index(Request $request)
    {
       if ($request->ajax()) {

            $query = (new TwoDigitRecordRepository($this->data))->execute();

            return Datatables::of($query)

                ->with('total', function () use ($query) {

                    $query = $query->clone()->get();

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
                    return $this->results[$q->time][$q->date] ?? NULL;
                })

                ->addColumn('betting_amount', function ($q) {
                    return number_format($q->betting_amount);
                })

                ->addColumn('win_amount', function ($q) {
                    return number_format($q->win_amount);
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
