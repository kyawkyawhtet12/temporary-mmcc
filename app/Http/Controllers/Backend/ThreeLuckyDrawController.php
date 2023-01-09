<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ThreeLuckyDrawController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $query = ThreeLuckyDraw::with('user', 'agent', 'threedigit')
                            ->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
            } else {
                $query = ThreeLuckyDraw::with('user', 'agent', 'threedigit')->latest();
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
                        return '<label class="badge badge-warning badge-pill">'.$digit->threedigit->number.'</label>';
                    })
                    ->addColumn('created_at', function ($digit) {
                        return date("F j, Y, g:i A", strtotime($digit->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('user', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('threedigit', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('number', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['user','agent','number'])
                    ->make(true);
        }
        return view('backend.admin.lucky_draws.3digit');
    }
}
