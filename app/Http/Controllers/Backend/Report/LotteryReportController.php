<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\TwoDigit;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use App\Http\Controllers\Controller;
use App\Models\LotteryTime;

class LotteryReportController extends Controller
{
    public function today()
    {
        // Thai
        $thai_one = TwoLuckyDraw::where([ ['lottery_time_id', '=', 1],['created_at', '>=', Carbon::today()],])
        ->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $thai_one_total = $thai_one->sum('amount');

        $thai_two = TwoLuckyDraw::where([
                    ['lottery_time_id', '=', 2],
                    ['created_at', '>=', Carbon::today()],
                ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $thai_two_total = $thai_two->sum('amount');

        // Dubai
        $dubai_one = TwoLuckyDraw::where([ ['lottery_time_id', '=', 3],['created_at', '>=', Carbon::today()],])
        ->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_one_total = $dubai_one->sum('amount');

        $dubai_two = TwoLuckyDraw::where([
                    ['lottery_time_id', '=', 4],
                    ['created_at', '>=', Carbon::today()],
                ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_two_total = $dubai_two->sum('amount');

        $dubai_three = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 5],
            ['created_at', '>=', Carbon::today()],
        ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_three_total = $dubai_three->sum('amount');

        $dubai_four = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 6],
            ['created_at', '>=', Carbon::today()],
        ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_four_total = $dubai_four->sum('amount');

        $dubai_five = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 7],
            ['created_at', '>=', Carbon::today()],
        ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_five_total = $dubai_five->sum('amount');

        $dubai_six = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 8],
            ['created_at', '>=', Carbon::today()],
        ])->selectRaw('SUM(amount) as amount, two_digit_id as two_digit_id')->groupBy('two_digit_id')->get();
        $dubai_six_total = $dubai_six->sum('amount');

        //

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
        
        $two_digits = TwoDigit::all();

        $thai_times = LotteryTime::where('type', 0)->get();
        $dubai_times = LotteryTime::where('type', 1)->get();

        return view('backend.admin.report.lottery-today', compact(
            'two_digits',
            'thai_one',
            'thai_two',
            'thai_one_total',
            'thai_two_total',
            'dubai_one',
            'dubai_two',
            'dubai_three',
            'dubai_four',
            'dubai_five',
            'dubai_six',
            'dubai_one_total',
            'dubai_two_total',
            'dubai_three_total',
            'dubai_four_total',
            'dubai_five_total',
            'dubai_six_total',
            'three_lucky_draws',
            'three_lucky_draws_last',
            'three_total',
            'three_total_last',
            'thai_times',
            'dubai_times'
        ));
    }
}
