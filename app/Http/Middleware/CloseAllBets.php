<?php

namespace App\Http\Middleware;

use App\Models\Enabled;
use Closure;
use Illuminate\Http\Request;

class CloseAllBets
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
        if( Enabled::first()->close_all_bets ){
            return redirect('/dashboard')->with('error', 'Close All Bets.');
        }

        return $next($request);
    }
}
