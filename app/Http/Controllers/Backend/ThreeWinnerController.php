<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThreeWinner;
use App\Models\ThreeDigitCompensation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ThreeWinnerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ThreeWinner::with('threeLuckyDraw', 'threeLuckyNumber')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('user', function ($digit) {
                        return '<label class="badge badge-primary badge-pill">'.$digit->threeLuckyDraw->user?->name.'</label>';
                    })
                    ->addColumn('agent', function ($digit) {
                        if ($digit->threeLuckyDraw->agent) {
                            return '<label class="badge badge-primary badge-pill">'.$digit->threeLuckyDraw->agent?->name.'</label>';
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('number', function ($digit) {
                        return '<label class="badge badge-success badge-pill">'.$digit->threeLuckyNumber->three_digit->number.'</label>';
                    })
                    ->addColumn('status', function ($digit) {
                        if ($digit->status == "Full") {
                            return '<label class="badge badge-success badge-pill">Full</label>';
                        } else {
                            return '<label class="badge badge-primary badge-pill">Za</label>';
                        }
                    })
                    ->addColumn('votes', function ($digit) {
                        if ($digit->status == "Za") {
                            return '<span>'.$digit->threeLuckyDraw->threedigit->number.'</span>';
                        }
                    })
                    ->addColumn('amount', function ($digit) {
                        return '<span>'.$digit->threeLuckyDraw->amount.' MMK</span>';
                    })
                    ->addColumn('total', function ($digit) {
                        $za = ThreeDigitCompensation::first();
                        if ($digit->status == "Full") {
                            return '<span>'. $digit->threeLuckyDraw->amount * $za->compensate .' MMK</span>';
                        } else {
                            return '<span>'. $digit->threeLuckyDraw->amount * $za->vote .' MMK</span>';
                        }
                    })
                    ->addColumn('created_at', function ($digit) {
                        return date("F j, Y", strtotime($digit->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('threeLuckyDraw', function ($w) use ($request) {
                                $w->whereHas('agent', function ($w) use ($request) {
                                    $search = $request->get('search');
                                    $w->where('name', 'LIKE', "%$search%");
                                });
                            });
                        }
                    })
                    ->rawColumns(['status', 'votes', 'agent','number','amount','total', 'user'])
                    ->make(true);
        }
        return view('backend.admin.winners.threewinner');
    }
}
