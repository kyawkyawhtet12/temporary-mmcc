<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\ThreeDigit;
use App\Models\LotteryTime;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use App\Models\TwoLuckyNumber;
use App\Models\ThreeLuckyNumber;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitCompensation;
use App\Models\ThreeDigitCompensation;

class LotteryReportController extends Controller
{
    public function today_2d()
    {
        $today = TwoLuckyDraw::whereDate('created_at', today())
                                ->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id, lottery_time_id')
                                ->groupBy('two_digit_id','lottery_time_id')
                                ->get();

        $thai_one = $today->where('lottery_time_id', 1);
        $thai_two = $today->where('lottery_time_id', 2);

        $thai_one_total = $thai_one->sum('amount');
        $thai_two_total = $thai_two->sum('amount');

        $two_digits = TwoDigit::all();
        $thai_times = LotteryTime::where('type', 0)->get();

        return view('backend.admin.report.today-2d', compact(
            'two_digits',
            'thai_one',
            'thai_two',
            'thai_one_total',
            'thai_two_total',
            'thai_times',
        ));

    }

    public function monthly_3d()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $today_last = $now->addDay('1')->toDateString();

        $first_day = $now->firstOfMonth()->toDateString();
        $second_day = $now->firstOfMonth()->addDay('1')->toDateString();
        $first_close = $now->firstOfMonth()->addDay('16')->toDateString();
        $end_day = $now->endOfMonth()->addDay('2')->toDateString();
        $three_lucky_draws = ThreeLuckyDraw::with('threedigit')->selectRaw('SUM(amount) as amount, three_digit_id')
                                            ->whereBetween('created_at', [$second_day, $first_close])
                                            ->orderBy('three_digit_id', 'asc')
                                            ->groupBy('three_digit_id')
                                            ->get();
        $three_total = $three_lucky_draws->sum('amount');
        if ($today == $first_day) {
            $first_close = Carbon::now()->subMonth()->addDay('16')->toDateString();
            $three_total_last = ThreeLuckyDraw::whereBetween('created_at', [$first_close, $today_last])->sum('amount');
            $three_lucky_draws_last = ThreeLuckyDraw::with('threedigit')->selectRaw('SUM(amount) as amount, three_digit_id')
                                                    ->whereBetween('created_at', [$first_close, $end_day])
                                                    ->orderBy('three_digit_id', 'asc')
                                                    ->groupBy('three_digit_id')->get();
        } else {
            $startDate = Carbon::now();
            $first_close = $startDate->firstOfMonth()->addDay('16')->toDateString();
            $three_total_last = ThreeLuckyDraw::whereBetween('created_at', [$first_close, $end_day])->sum('amount');
            $three_lucky_draws_last = ThreeLuckyDraw::with('threedigit')->selectRaw('SUM(amount) as amount, three_digit_id')
                                                    ->whereBetween('created_at', [$first_close, $end_day])
                                                    ->orderBy('three_digit_id', 'asc')
                                                    ->groupBy('three_digit_id')->get();
        }

        return view('backend.admin.report.monthly-3d', compact(
            'three_lucky_draws',
            'three_lucky_draws_last',
            'three_total',
            'three_total_last',
        ));
    }

    // results and detail
    public function two_digits(Request $request)
    {
        if ($request->ajax()) {
            $query = TwoLuckyNumber::with('two_digit', 'lottery_time')->orderBy('date', 'desc'
                                        )->orderBy('lottery_time_id', 'desc');
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('number', function ($number) {
                        return $number->two_digit?->number;
                    })
                    ->addColumn('lottery_time', function ($number) {
                        return $number->lottery_time->time;
                    })
                    ->addColumn('date', function ($number) {
                        return date("F j, Y", strtotime($number->date));
                    })
                    ->addColumn('action', function ($number) {
                        $btn = "<a href='/admin/two-digits-results/$number->id' class='btn btn-success'>Detail</a>";

                        return $btn;
                    })
                    ->rawColumns(['number','lottery_time','action'])
                    ->make(true);
        }

        return view('backend.admin.report.result.2d');
    }

    public function two_digits_detail(Request $request, $id)
    {
        $data = TwoLuckyNumber::with('two_digit','lottery_time', 'winners','winners.twoLuckyDraw')->findOrFail($id);

        $two_digits = TwoDigit::all();

        $agent_id = ($request->agent != 'all') ? $request->agent : NULL;

        $win_betting = $data->winners->when($agent_id, function($query, $agent_id) {
                                        $query->where('twoLuckyDraw.agent_id', $agent_id);
                                    })?->sum('twoLuckyDraw.amount');

        $draw = TwoLuckyDraw::when($agent_id, function($query, $agent_id) {
                                    $query->where('agent_id', $agent_id);
                                })
                                ->where('lottery_time_id', $data->lottery_time_id)
                                ->whereDate('created_at', $data->date)
                                ->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id, za as za')
                                ->groupBy('two_digit_id','za')
                                ->get();

        $agents = Agent::select('id','name')->get();

        $current_odds = TwoDigitCompensation::first()->compensate;
        $odds = count($draw) ? $draw[0]->za : $current_odds;

        return view('backend.admin.report.result.2d-detail', compact('two_digits', 'data', 'win_betting' ,'odds', 'draw','agents'));
    }

    // 3D

    public function three_digits(Request $request)
    {
        if ($request->ajax()) {
            $query = ThreeLuckyNumber::with('three_digit')->orderBy('date', 'desc');
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('number', function ($number) {
                        return $number->three_digit?->number;
                    })
                    ->addColumn('date', function ($number) {
                        return date("F j, Y", strtotime($number->created_at));
                    })
                    ->addColumn('action', function ($number) {
                        $btn = "<a href='/admin/three-digits-results/$number->id' class='btn btn-success'>Detail</a>";

                        return $btn;
                    })
                    ->rawColumns(['number','action'])
                    ->make(true);
        }

        return view('backend.admin.report.result.3d');
    }

    public function three_digits_detail(Request $request, $id)
    {
        $data = ThreeLuckyNumber::with('three_digit','winners','winners.threeLuckyDraw')->findOrFail($id);
        $three_digits = ThreeDigit::all();

        $agent_id = ($request->agent != 'all') ? $request->agent : NULL;

        $win_betting = $data->winners->when($agent_id, function($query, $agent_id) {
                                            $query->where('threeLuckyDraw.agent_id', $agent_id);
                                        })?->sum('threeLuckyDraw.amount');

        $draw = ThreeLuckyDraw::when($agent_id, function($query, $agent_id) {
                                    $query->where('agent_id', $agent_id);
                                })
                                ->where('round', $data->round)
                                ->selectRaw('SUM(amount) as amount, three_digit_id as three_digit_id, za as za')
                                ->groupBy('three_digit_id','za')
                                ->get();

        $agents = Agent::select('id','name')->get();
        $current_odds = ThreeDigitCompensation::first()->compensate;
        $odds = count($draw) ? $draw[0]->za : $current_odds;

        return view('backend.admin.report.result.3d-detail', compact('three_digits', 'data', 'win_betting', 'odds', 'draw', 'agents'));
    }
}
