<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitCompensation;
use App\Models\ThreeDigitCompensation;

class TwoDigitCompensationController extends Controller
{
    public function index()
    {
        $twocompensate = TwoDigitCompensation::orderBy('id', 'DESC')->get();
        $threecompensate = ThreeDigitCompensation::orderBy('id', 'DESC')->get();
        return view('backend.admin.compensate.index', compact('twocompensate', 'threecompensate'));
    }

    public function updateTwoCompensate(Request $request)
    {
        TwoDigitCompensation::find($request->pk)->update(['compensate' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateThreeCompensate(Request $request)
    {
        ThreeDigitCompensation::find($request->pk)->update(['compensate' => $request->value]);
        return response()->json(['success'=>'done']);
    }

    public function updateVote(Request $request)
    {
        ThreeDigitCompensation::find($request->pk)->update(['vote' => $request->value]);
        return response()->json(['success'=>'done']);
    }
}
