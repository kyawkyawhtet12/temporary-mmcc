<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ThreeDigit;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ThreeDigitDisableController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ThreeDigit::where('status', 1)->orWhere('amount', '>', 0)->get();

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('status', function ($number) {
                        return $number->status;
                    })
                    ->addColumn('action', function ($number) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Edit" class="btn btn-danger deleteNumber"> Delete </a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        return view("backend.admin.3d-close.index");
    }

    public function store(Request $request)
    {
        // return $request->all();
        $digit = ThreeDigit::where("number", $request->three_digit_number)->first();
        
        if (!$digit) {
            return back()->with('error', '* Error');
        }

        $digit->update(['status' => $request->amount ? 0 : 1 , 'amount' => $request->amount ?: 0 ]);

        return back()->with('success', '* Success');
    }

    public function enable(Request $request, $id)
    {
        $digit = ThreeDigit::find($id);

        if (!$digit) {
            return response()->json("error");
        }

        $digit->update([ 'status' => 0]);

        return response()->json('success');
    }
}
