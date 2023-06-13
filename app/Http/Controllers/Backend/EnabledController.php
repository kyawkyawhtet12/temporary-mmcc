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

    // Close (or) Open For All Bets
    public function close_all_bets()
    {
        $data = Enabled::find(1);

        $data->update([
            'close_all_bets' => !$data->close_all_bets
        ]);

        return back();
    }
}
