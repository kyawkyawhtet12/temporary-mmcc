<?php

namespace App\Http\Controllers\Backend;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LotteryTime;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        // return view('backend.agent.index');

        // code
        $referral_code = Auth::user()->referral_code;
        $users_id = User::where('referral_code', $referral_code)->pluck('id')->toArray();
        $yesterday = date("Y-m-d 16:30:00", strtotime("yesterday"));
        $today = date("Y-m-d 16:30:00");
        $evening = "16:30:00";
        $now = Carbon::now()->format('H:i:s');

        $total_user = User::where('referral_code', $referral_code)->count();

        // 2D
        $thai_one = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 1],
            ['created_at', '>=', Carbon::today()],
        ])->whereIn('user_id', $users_id)->sum('amount');

        $thai_two = TwoLuckyDraw::where([
                                ['lottery_time_id', '=', 2],
                                ['created_at', '>=', Carbon::today()],
                            ])->whereIn('user_id', $users_id)->sum('amount');

        $dubai_one = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 3],
            ['created_at', '>=', Carbon::today()],
        ])->whereIn('user_id', $users_id)->sum('amount');
                    
        $dubai_two = TwoLuckyDraw::where([
                                ['lottery_time_id', '=', 4],
                                ['created_at', '>=', Carbon::today()],
                            ])->whereIn('user_id', $users_id)->sum('amount');

        $dubai_three = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 5],
            ['created_at', '>=', Carbon::today()],
        ])->whereIn('user_id', $users_id)->sum('amount');
                                        
        $dubai_four = TwoLuckyDraw::where([
                                ['lottery_time_id', '=', 6],
                                ['created_at', '>=', Carbon::today()],
                            ])->whereIn('user_id', $users_id)->sum('amount');

        $dubai_five = TwoLuckyDraw::where([
            ['lottery_time_id', '=', 7],
            ['created_at', '>=', Carbon::today()],
        ])->whereIn('user_id', $users_id)->sum('amount');
                                                            
        $dubai_six = TwoLuckyDraw::where([
                                ['lottery_time_id', '=', 8],
                                ['created_at', '>=', Carbon::today()],
                            ])->whereIn('user_id', $users_id)->sum('amount');

        // 3D
        $now = Carbon::now();
        $today = $now->toDateString();
        $today_last = $now->addDay('1')->toDateString();
        $first_day = $now->firstOfMonth()->toDateString();
        $second_day = $now->firstOfMonth()->addDay('1')->toDateString();
        $first_close = $now->firstOfMonth()->addDay('16')->toDateString();
        $end_day = $now->endOfMonth()->addDay('2')->toDateString();
        $three_total_first = ThreeLuckyDraw::whereBetween('created_at', [$second_day, $first_close])
                            ->whereIn('user_id', $users_id)->sum('amount');
        if ($today == $first_day) {
            $first_close = Carbon::now()->subMonth()->addDay('16')->toDateString();
            $three_total_last = ThreeLuckyDraw::whereBetween('created_at', [$first_close, $today_last])
                                        ->whereIn('user_id', $users_id)->sum('amount');
        } else {
            $startDate = Carbon::now();
            $first_close = $startDate->firstOfMonth()->addDay('16')->toDateString();
            $three_total_last = ThreeLuckyDraw::whereBetween('created_at', [$first_close, $end_day])
                                        ->whereIn('user_id', $users_id)->sum('amount');
        }

        $three_lucky_draws = ThreeLuckyDraw::select(array(DB::Raw('sum(amount) as amount'),DB::Raw('DATE(created_at) day')))
                            ->whereIn('user_id', $users_id)
                            ->groupBy('day')
                            ->get();

        // return $three_lucky_draws;
        $thai_times = LotteryTime::where('type', 0)->get();
        $dubai_times = LotteryTime::where('type', 1)->get();
        
        if ($request->ajax()) {
            $query = TwoLuckyDraw::select(array(DB::Raw('sum(amount) as amount'),DB::Raw('DATE(created_at) day')))
                            ->whereIn('user_id', $users_id)
                            ->groupBy('day');

            return Datatables::of($query)->addIndexColumn()
                        ->addColumn('amount', function ($user) {
                            return '<label class="badge badge-success badge-pill">'.$user->amount.' MMK</label>';
                        })
                        ->rawColumns(['amount'])
                        ->make(true);
        }

        // return $three_total_last;
        
        return view('backend.agent.index', compact(
            'total_user',
            'thai_one',
            'thai_two',
            'dubai_one',
            'dubai_two',
            'dubai_three',
            'dubai_four',
            'dubai_five',
            'dubai_six',
            'thai_times',
            'dubai_times',
            'three_total_first',
            'three_total_last',
            'three_lucky_draws'
        ));
    }

    public function withdrawHistory(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentWithdraw::with('agent', 'provider')->where('agent_id', Auth::guard('agent')->user()->id)->latest();
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('provider', function ($cashout) {
                    return '<label class="badge badge-info badge-pill">'.$cashout->provider->name.'</label>';
                })
                ->addColumn('agent', function ($cashout) {
                    return '<label class="badge badge-info badge-pill">'.$cashout->agent->name.'</label>';
                })
                ->addColumn('amount', function ($cashout) {
                    return '<label class="badge badge-primary badge-pill">'.$cashout->amount.' MMK</label>';
                })
                ->addColumn('status', function ($cashout) {
                    return  '<label class="badge badge-success badge-pill">'.$cashout->status.'</label>';
                })
                ->addColumn('created_at', function ($cashout) {
                    return date("F j, Y, g:i A", strtotime($cashout->created_at));
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->whereHas('provider', function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('name', 'LIKE', "%$search%");
                            $w->orWhere('phone', 'LIKE', "%$search%");
                        })
                        ->orwhereHas('agent', function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->where('name', 'LIKE', "%$search%");
                            $w->orWhere('phone', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['provider','agent','amount','status'])
                ->make(true);
        }
        return view('backend.agents.history');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $agent = Auth::guard('agent')->user();
        return view('backend.agents.profile', compact('agent'));
    }
}
