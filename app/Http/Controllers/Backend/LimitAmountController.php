<?php

namespace App\Http\Controllers\Backend;

use App\Models\LimitAmount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LimitAmountController extends Controller
{
    public function index()
    {
        $limit_amounts = LimitAmount::orderBy('id', 'DESC')->get();
        return view('backend.admin.limit_amounts.index', compact('limit_amounts'));
    }

    public function updateMin(Request $request)
    {
        LimitAmount::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateMax(Request $request)
    {
        LimitAmount::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['success'=>'done']);
    }
}
