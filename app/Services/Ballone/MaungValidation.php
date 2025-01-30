<?php

namespace App\Services\Ballone;

use App\Models\FootballBet;
use App\Models\FootballMatch;
use App\Models\FootballMaung;
use App\Models\MaungTeamSetting;
use App\Models\FootballMaungGroup;
use App\Models\FootballMaungTransaction;
use Illuminate\Support\Facades\Auth;

class MaungValidation
{
    public function handle($request)
    {
        // $this->checkOld($request);

        $betting = [];

        foreach ($request->data as $dt) {
            array_push($betting, ['match' => $dt['match'], 'type' => $dt['type']] );
        }

        $old = FootballMaungTransaction::where("betting", json_encode($betting))
                                        ->where("user_id", auth()->id())
                                        ->exists();

        if($old){
            throw new \Exception("ပုံစံတူ လောင်းလို့မရပါ");
        }

        $min_limit_amount =  Auth::user()->maung_min_amount();
        $max_limit_amount =  Auth::user()->maung_max_amount();

        if ($request->amount > Auth::user()->amount) {
            throw new \Exception("လက်ကျန်ငွေမလုံလောက်ပါ။");
        }

        if ($request->amount < $min_limit_amount) {
            throw new \Exception("အနည်းဆုံးလောင်းငွေပမာဏမှာ $min_limit_amount ဖြစ်သည်။");
        }

        if ($request->amount > $max_limit_amount) {
            throw new \Exception("အများဆုံးလောင်းငွေပမာဏမှာ $max_limit_amount ဖြစ်သည်။");
        }

        $limit = MaungTeamSetting::first();

        if (count($request->data) < $limit->min_teams || count($request->data) > $limit->max_teams) {
            throw new \Exception("အနည်းဆုံး $limit->min_teams သင်း နှင့် အများဆုံး $limit->max_teams သင်း ကြားကစားပေးပါ ။");
        }

        foreach ($request->data as $dt) {

            $match = FootballMatch::with('maungfees')->find($dt['match']);

            if ($match->calculate_maung || now()->gte($match->date_time) ) {
                throw new \Exception("လောင်းကြေး ပိတ်သွားပါပြီ။");
            }

            if( $match->matchStatus?->all_close){
                throw new \Exception("လောင်းကြေး ပိတ်သွားပါပြီ။");
            }

            if ($match->maungfees->id !== (int)$dt['fees']) {
                throw new \Exception("လောင်းကြေး ပြောင်းလဲမှုရှိပါသည်။");
            }
        }
    }
}
