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
            $query = TwoWinner::with('twoLuckyDraw.agent', 'twoLuckyNumber.two_digit')->latest();

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('user', function ($digit) {
                        return $digit->twoLuckyDraw->user?->name;
                    })
                    ->addColumn('user_id', function ($digit) {
                        return $digit->twoLuckyDraw->user?->user_id;
                    })
                    ->addColumn('agent', function ($digit) {
                        if ($digit->twoLuckyDraw->agent) {
                            return $digit->twoLuckyDraw->agent?->name;
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
                        return date("F j, Y", strtotime($digit->twoLuckyDraw->created_at));
                    })
                    ->filter(function ($instance) use ($request) {

                        $search = $request->get('search');

                        if (!empty($search)) {

                            $instance->whereHas('twoLuckyDraw', function ($w) use ($search) {

                                $w->whereHas('user', function ($w) use ($search) {
                                    $w->where('name', 'LIKE', "%$search%");
                                    $w->orWhere('user_id', 'LIKE', "%$search%");
                                });

                                $w->orWhereHas('agent', function ($w) use ($search) {
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
