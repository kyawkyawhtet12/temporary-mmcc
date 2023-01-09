<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Disable;
use App\Models\Enabled;
use Illuminate\Http\Request;

class ThreeLimitDateTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $enabled = Enabled::first();
        if ($enabled->three_status == 1) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available. dude!',
            ]);
        }
        $disable = Disable::first();
        $now = Carbon::now()->toDateTimeString();
        $disable_time = date("Y-m-d H:i:s", strtotime($disable->datetime));
        $close_time = date("Y-m-d 11:59:00", strtotime($disable->datetime));
        if ($now > $disable_time && $now < $close_time) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available by Admin. dude!',
            ]);
        }
        // $now = Carbon::now();
        // $close = Carbon::now()->toDateTimeString();
        // $first_day = $now->firstOfMonth()->toDateString();
        // $first_day = $first_day .' '."13:00:00";
        // $first_close = $now->firstOfMonth()->addDay('16')->toDateTimeString();
        // $sixteenth_day = $now->firstOfMonth()->addDay('15')->toDateString();
        // $sixteenth_day = $sixteenth_day .' '."13:00:00";
        // $sixteenth_close = $now->firstOfMonth()->addDay('1')->toDateString();
        // if ($close < $first_day && $close > $first_close) {
        //     return response()->json([
        //         'status' => 401,
        //         'message' => 'Unauthorised',
        //         'error' => 'The service is not available after 1pm on the 1st of every month. dude!',
        //     ]);
        // }elseif ($close > $sixteenth_day && $close < $sixteenth_close) {
        //     return response()->json([
        //         'status' => 401,
        //         'message' => 'Unauthorised',
        //         'error' => 'The service is not available after 1pm on the 16th of each month. dude!',
        //     ]);
        // }
        return $next($request);
    }
}
