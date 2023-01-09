<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LotteryTime;
use Carbon\Carbon;

class LotteryTimeController extends Controller
{
    public function index()
    {
        $times = LotteryTime::where('type', 0)->get();
        return view("backend.admin.lottery-time.index", compact('times'));
    }

    public function edit($id)
    {
        $time = LotteryTime::findOrFail($id);
        return view("backend.admin.lottery-time.edit", compact('time'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();

        $time = Carbon::parse($request->time)->format('g:i A');

        $request->validate([
            'time' => 'required',
            'type' => 'required'
        ]);

        LotteryTime::findOrFail($id)->update([
            'time' => $time,
            'type' => $request->type,
        ]);

        return redirect()->route('lottery-time.index')->with('success', 'Success');
    }
}
