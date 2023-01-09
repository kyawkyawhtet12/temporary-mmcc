<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TwoLuckyDraw;
use App\Models\Cashout;
use App\Models\LotteryTime;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class TwoLuckyDrawController extends Controller
{
    public function index(Request $request)
    {
        $times = LotteryTime::all();
        
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $query = TwoLuckyDraw::with('user', 'agent', 'twodigit', 'lottery_time')->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
            } else {
                $query = TwoLuckyDraw::with('user', 'agent', 'twodigit', 'lottery_time')->latest();
            }

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('user', function ($digit) {
                        return '<label class="badge badge-success badge-pill">'.$digit->user?->name.'</label>';
                    })
                    ->addColumn('agent', function ($digit) {
                        if ($digit->agent) {
                            return '<label class="badge badge-success badge-pill">'.$digit->agent?->name.'</label>';
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('number', function ($digit) {
                        return '<label class="badge badge-warning badge-pill">'.$digit->twodigit->number.'</label>';
                    })
                    ->addColumn('lottery_time', function ($row) {
                        return '<span>'.$row->lottery_time->time.'</span>';
                    })
                    ->addColumn('created_at', function ($digit) {
                        return date("F j, Y, g:i A", strtotime($digit->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('lottery_time'))) {
                            $instance->where(function ($w) use ($request) {
                                $lottery_time = $request->get('lottery_time');
                                $w->orWhere('lottery_time_id', 'LIKE', "%$lottery_time%");
                            });
                        }
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('agent', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('twodigit', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('number', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['user','agent','number','lottery_time'])
                    ->make(true);
        }
        return view('backend.admin.lucky_draws.2digit', compact('times'));
    }
}
