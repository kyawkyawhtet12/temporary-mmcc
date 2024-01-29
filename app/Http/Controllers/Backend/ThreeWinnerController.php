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
                    ->addColumn('user_id', function ($digit) {
                        return $digit->threeLuckyDraw->user?->user_id;
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
                    ->addColumn('amount', function ($digit) {
                        return '<span>'.$digit->threeLuckyDraw->amount.' MMK</span>';
                    })
                    ->addColumn('za', function ($digit) {
                        return $digit->threeLuckyDraw->za;
                    })
                    ->addColumn('total', function ($digit) {
                        return '<span>'. $digit->threeLuckyDraw->win_amount.' MMK</span>';
                    })
                    ->addColumn('created_at', function ($digit) {
                        return date("F j, Y", strtotime($digit->created_at));
                    })
                    ->filter(function ($instance) use ($request) {

                        $search = $request->get('search');

                        if ( !empty($search) ) {

                            $instance->whereHas('threeLuckyDraw', function ($w) use ($search) {

                                $w->whereHas('agent', function ($w) use ($search) {
                                    $w->where('name', 'LIKE', "%$search%");
                                    $w->orWhere('user_id', 'LIKE', "%$search%");
                                });

                                $w->orWhereHas('agent', function ($w) use ($search) {
                                    $w->where('name', 'LIKE', "%$search%");
                                });
                            });
                        }
                    })
                    ->rawColumns(['status', 'agent','number','amount','total', 'user'])
                    ->make(true);
        }
        return view('backend.admin.winners.threewinner');
    }
}
