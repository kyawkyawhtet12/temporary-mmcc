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
        // if ($request->ajax()) {
        //     $query = ThreeDigit::where('status', 1)->orWhere('amount', '>', 0)->get();

        //     return Datatables::of($query)
        //             ->addIndexColumn()
        //             ->addColumn('status', function ($number) {
        //                 return $number->status;
        //             })
        //             ->addColumn('action', function ($number) {
        //                 $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Edit" class="btn btn-danger deleteNumber"> Delete </a>';
        //                 return $btn;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }

        $data = ThreeDigit::all();

        return view("backend.admin.3d-close.index",compact('data'));
    }

    public function changeTwoDigitEnable(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => null
        ]);
        return response()->json('success');
    }

    public function changeThreeDigitDisable(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'status' => $request->status,
            'amount' => 0,
            'date' => $request->date
        ]);
        return response()->json('success');
    }

    public function changeThreeDigitSubmit(Request $request)
    {
        ThreeDigit::whereIn('id', explode(",", $request->ids))->update([
            'amount' => $request->amount,
            'date' => $request->date
        ]);
        return response()->json('success');
    }
}
