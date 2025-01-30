<?php

namespace App\Services\Ballone;

use App\Models\FootballMatch;
use Illuminate\Support\Facades\Auth;

class BodyLimitCheck
{
    protected $limit_error = [];

    public function handle($request)
    {
        $min_limit_amount =  Auth::user()->body_min_amount();
        $max_limit_amount =  Auth::user()->body_max_amount();

        foreach ($request->data as $x => $dt) {

            $match = FootballMatch::with('bodyfees')->find($dt['match']);

            $bettingType = $match->getBettingType($dt['type']);

            $transactions_user = $match->getBodyAmountByUser($dt['type']);

            $limit_amount = min(
                +$match->limit_amount - $transactions_user ,
                $max_limit_amount - $transactions_user
            );

            if ($request->amount[$x] > $limit_amount) {

                $error = [
                    'error' => $this->getErrorMessage($limit_amount, $match->match_format, $bettingType),
                    'football_match' => $match->match_format,
                    'selected' => $bettingType,
                    'available_amount' => $limit_amount
                ];

                array_push($this->limit_error, $error);
            }
        }

        return $this->limit_error;
    }

    protected function getErrorMessage($max_amount, $match_format, $bettingType)
    {
        if ($max_amount > 0) {
            return  "{$match_format} ပွဲကို {$bettingType} ဘက်မှ {$max_amount} ဖိုးသာ လောင်းလို့ရပါတော့မည်။";
        } else {
            return  "{$match_format} ပွဲကို {$bettingType} ဘက်မှ လောင်းမရတော့ပါ။";
        }
    }
}
