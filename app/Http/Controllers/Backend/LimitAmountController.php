<?php

namespace App\Http\Controllers\Backend;

use App\Models\LimitAmount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LimitAmountController extends Controller
{
    // 2d
    public function limit_2d()
    {
        $limit_amounts = LimitAmount::orderBy('id', 'DESC')->get();
        return view('backend.admin.limit_amounts.2d', compact('limit_amounts'));
    }

    public function updateMin_2d(Request $request)
    {
        LimitAmount::find($request->pk)->update([ 'two_min_amount' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateMax_2d(Request $request)
    {
        LimitAmount::find($request->pk)->update([ 'two_max_amount' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    // 3d
    public function limit_3d()
    {
        $limit_amounts = LimitAmount::orderBy('id', 'DESC')->get();
        return view('backend.admin.limit_amounts.3d', compact('limit_amounts'));
    }

    public function updateMin_3d(Request $request)
    {
        LimitAmount::find($request->pk)->update( ['three_min_amount'=> $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateMax_3d(Request $request)
    {
        LimitAmount::find($request->pk)->update(['three_max_amount' => $request->value]);
        return response()->json(['success'=>'done']);
    }
}
