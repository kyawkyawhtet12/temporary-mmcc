<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitCompensation;
use App\Models\ThreeDigitCompensation;

class LimitCompensationController extends Controller
{
    public function limit_2d()
    {
        $data = TwoDigitCompensation::find(1);
        return view('backend.admin.compensate.2d', compact('data'));
    }

    public function updateTwoCompensate(Request $request)
    {
        TwoDigitCompensation::find($request->pk)->update(['compensate' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    //

    public function limit_3d()
    {
        $data = ThreeDigitCompensation::find(1);
        return view('backend.admin.compensate.3d', compact('data'));
    }

    public function updateThreeCompensate(Request $request)
    {
        ThreeDigitCompensation::find($request->pk)->update(['compensate' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    // public function updateVote(Request $request)
    // {
    //     ThreeDigitCompensation::find($request->pk)->update(['vote' => $request->value]);
    //     return response()->json(['success'=>'done']);
    // }
}
