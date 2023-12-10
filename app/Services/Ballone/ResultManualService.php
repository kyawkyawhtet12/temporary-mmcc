<?php

namespace App\Services\Ballone;

class ResultManualService
{
    public function handle($request, $result)
    {
        $request->validate([
            'home' => 'required|numeric',
            'away' => 'required|numeric',
            'over'  => 'required|numeric',
            'under' => 'required|numeric'
        ]);

        $result->update([
            'home' => $request->home,
            'away' => $request->away,
            'over'  => $request->over,
            'under' => $request->under
        ]);
    }
}
