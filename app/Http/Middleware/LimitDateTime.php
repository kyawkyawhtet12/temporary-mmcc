<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Enabled;
use Illuminate\Http\Request;

class LimitDateTime
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
        if ($enabled->two_status == 1) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available. dude!',
            ]);
        }
        $now = Carbon::now()->format('H:i:s');
        $today = Carbon::now();
        if ($today->isWeekend()) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available on weekends. dude!',
            ]);
        }
        $morningTime = date("Y-m-d 11:55:00");
        $morningTime = Carbon::parse($morningTime)->format('H:i:s');
        $morningClose = date("Y-m-d 12:02:00");
        $morningClose = Carbon::parse($morningClose)->format('H:i:s');
        $eveningTime = date("Y-m-d 15:55:00");
        $eveningTime = Carbon::parse($eveningTime)->format('H:i:s');
        $eveningClose = date("Y-m-d 16:31:00");
        $eveningClose = Carbon::parse($eveningClose)->format('H:i:s');
        if ($now > $morningTime && $now <= $morningClose) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available between 11:55 AM and 12:02 noon. dude!',
            ]);
        }
        if ($now > $eveningTime && $now <= $eveningClose) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorised',
                'error' => 'The service is not available between 3:55 PM and 4:31 PM.dude!',
            ]);
        }
        return $next($request);
    }
}
