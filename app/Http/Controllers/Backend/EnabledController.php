<?php

namespace App\Http\Controllers\Backend;

use App\Models\Enabled;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnabledController extends Controller
{
    public function twoThaiLotteryStatus(Request $request)
    {
        $enable = Enabled::find(1);
        $enable->two_thai_status = $request->status;
        $enable->save();
        return response()->json(['message' => 'Lottery status changed successfully.']);
    }

    public function threeLotteryStatus(Request $request)
    {
        $enable = Enabled::find(1);
        $enable->three_status = $request->status;
        $enable->save();
        return response()->json(['message' => 'Lottery status changed successfully.']);
    }
}
