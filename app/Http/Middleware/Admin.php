<?php

namespace App\Http\Middleware;

use App\Models\TwoLuckyNumber;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
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
        TwoLuckyNumber::firstOrCreate(
            [ 'date' => today()->format('Y-m-d'), 'lottery_time_id' => 1 ],
            [ 'two_digit_id' => NULL ]
        );

        TwoLuckyNumber::firstOrCreate(
            [ 'date' => today()->format('Y-m-d'), 'lottery_time_id' => 2 ],
            [ 'two_digit_id' => NULL ]
        );

        return $next($request);
    }
}
