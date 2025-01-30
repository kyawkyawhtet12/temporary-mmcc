<?php

namespace App\Services\Ballone;

use App\Models\FootballMatch;
use Illuminate\Support\Facades\Auth;

class BodyValidation
{
    public function handle($request)
    {
        $min_limit_amount =  Auth::user()->body_min_amount();
        $max_limit_amount =  Auth::user()->body_max_amount();

        $total_amount = array_sum($request->amount);

        if ($total_amount > Auth::user()->amount) {
            throw new \Exception("လက်ကျန်ငွေမလုံလောက်ပါ။");
        }

        if (min($request->amount) < $min_limit_amount) {
            throw new \Exception("အနည်းဆုံးလောင်းငွေပမာဏမှာ $min_limit_amount ဖြစ်သည်။");
        }

        // if (max($request->amount) > $max_limit_amount) {
        //     throw new \Exception("အများဆုံးလောင်းငွေပမာဏမှာ $max_limit_amount ဖြစ်သည်။");
        // }

        foreach ($request->data as $x => $dt) {

            $match = FootballMatch::with('bodyfees')->find($dt['match']);

            if ($match->calculate_body || now()->gte($match->date_time) ) {
                throw new \Exception("လောင်းကြေး ပိတ်သွားပါပြီ။");
            }

            if( $match->matchStatus?->all_close){
                throw new \Exception("လောင်းကြေး ပိတ်သွားပါပြီ။");
            }

            if ($match->bodyfees->id !== (int)$dt['fees']) {
                throw new \Exception("လောင်းကြေး ပြောင်းလဲမှုရှိပါသည်။");
            }

            // $old_amount = $match->body_betting_amount($dt['type']);

            $bettingType = $match->getBettingType($dt['type']);

            $transactions = $match->bodies->where("type", $dt['type']);

            $transactions_all = $transactions->sum("bet.amount");
            $transactions_user = $transactions->where('user_id' , auth()->id())->sum("bet.amount");

            $available_amount = +$match->limit_amount - $transactions_user; // per match


            if( $request->amount[$x] > $available_amount ){
                throw new \Exception($this->getMessage($match->match_format, $bettingType, $available_amount));
            }

            $max_amount = $max_limit_amount - $transactions_all; // per agent

            if( $request->amount[$x] > $max_amount ){
                throw new \Exception($this->getMessage($match->match_format, $bettingType, $max_amount));
            }

        }
    }

    protected function getMessage($match_format, $type, $limit)
    {
        if( $limit > 0 ){
            return "{$match_format} ပွဲကို {$type} ဘက်မှ  အများဆုံးလောင်းငွေပမာဏ {$limit} ဖိုးသာ လောင်းလို့ရပါတော့မည်။";
        }

        return "{$match_format} ပွဲကို {$type} ဘက်မှ လောင်းမရတော့ပါ။";
    }
}
