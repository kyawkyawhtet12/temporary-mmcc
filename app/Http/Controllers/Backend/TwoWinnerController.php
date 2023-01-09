<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TwoWinner;
use App\Models\TwoDigitCompensation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class TwoWinnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TwoWinner::with('twoLuckyDraw', 'twoLuckyNumber')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('user', function ($digit) {
                        return '<label class="badge badge-primary badge-pill">'.$digit->twoLuckyDraw->user?->name.'</label>';
                    })
                    ->addColumn('agent', function ($digit) {
                        if ($digit->twoLuckyDraw->agent) {
                            return '<label class="badge badge-primary badge-pill">'.$digit->twoLuckyDraw->agent?->name.'</label>';
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('number', function ($digit) {
                        return '<label class="badge badge-success badge-pill">'.$digit->twoLuckyNumber->two_digit->number.'</label>';
                    })
                    ->addColumn('lottery_time', function ($digit) {
                        return $digit->twoLuckyNumber->lottery_time->time;
                    })
                    ->addColumn('amount', function ($digit) {
                        return $digit->twoLuckyDraw->amount.' MMK';
                    })
                    ->addColumn('total', function ($digit) {
                        $za = TwoDigitCompensation::first();
                        return '<span>'. $digit->twoLuckyDraw->amount * $za->compensate .' MMK</span>';
                    })
                    ->addColumn('created_at', function ($digit) {
                        return date("F j, Y", strtotime($digit->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('twoLuckyDraw', function ($w) use ($request) {
                                $w->whereHas('user', function ($w) use ($request) {
                                    $search = $request->get('search');
                                    $w->where('name', 'LIKE', "%$search%");
                                });
                            });
                        }
                    })
                    ->rawColumns(['user','agent', 'number','lottery_time','amount','total'])
                    ->make(true);
        }
        return view('backend.admin.winners.twowinner');
    }
}
