<?php

namespace App\Services\Ballone;

class ResultManualService
{
    public function handle($request, $result)
    {
        if( $result->body_error){
            $request->validate([
                'home' => 'required|numeric',
                'away' => 'required|numeric'
            ]);

            $result->update([
                'home' => $request->home,
                'away' => $request->away
            ]);
        }

        if( $result->goal_error){
            $request->validate([
                'over'  => 'required|numeric',
                'under' => 'required|numeric'
            ]);

            $result->update([
                'over'  => $request->over,
                'under' => $request->under
            ]);
        }
    }
}
