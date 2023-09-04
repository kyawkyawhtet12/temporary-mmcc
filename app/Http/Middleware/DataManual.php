<?php

namespace App\Http\Middleware;

use App\Models\TwoLuckyNumber;
use Closure;
use Illuminate\Http\Request;

class DataManual
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

        $check = TwoLuckyNumber::where('date', today()->format('Y-m-d'))
                            ->whereIn('lottery_time_id', [1,2])
                            ->doesntExist();

        if( $check ){
            TwoLuckyNumber::firstOrCreate(
                [ 'date' => today()->format('Y-m-d'), 'lottery_time_id' => 1 ],
                [ 'two_digit_id' => NULL ]
            );

            TwoLuckyNumber::firstOrCreate(
                [ 'date' => today()->format('Y-m-d'), 'lottery_time_id' => 2 ],
                [ 'two_digit_id' => NULL ]
            );
        }

        return $next($request);
    }
}
