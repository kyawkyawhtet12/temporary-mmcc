<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Services\Ballone\BodyLimitCheck;

class BodyValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     protected $user, $min_amount, $max_amount;

     protected $error = [], $limit_error = [], $limits;

     protected $today_transactions = [];

     public function __construct()
     {
         $this->user = auth()->user();

         $this->min_amount = $this->user->body_min_amount();
         $this->max_amount = $this->user->body_max_amount();

     }

    public function handle(Request $request, Closure $next)
    {

        if (array_sum($request->amount) > $this->user->amount) {
            $error_msg = "လက်ကျန်ငွေမလုံလောက်ပါ။";

            return response()->json(['error' => $error_msg]);
        }

        if (min($request->amount) < $this->min_amount) {
            $error_msg = "အနည်းဆုံးလောင်းငွေပမာဏမှာ $this->min_amount ဖြစ်သည်။";

            return response()->json(['error' => $error_msg ]);
        }

        foreach ($request->data as $x => $dt) {

            $match = FootballMatch::with('bodyfees')->find($dt['match']);

            if ($match->calculate_body || now()->gte($match->date_time) ) {
                $error_msg = "လောင်းကြေး ပိတ်သွားပါပြီ။";

                return response()->json(['error' => $error_msg ]);
            }

            if( $match->matchStatus?->all_close){
                $error_msg = "လောင်းကြေး ပိတ်သွားပါပြီ။";

                return response()->json(['error' => $error_msg ]);
            }

            if ($match->bodyfees->id !== (int)$dt['fees']) {
                $error_msg = "လောင်းကြေး ပြောင်းလဲမှုရှိပါသည်။";

                return response()->json(['error' => $error_msg ]);
            }

        }

        $this->limit_error = (new BodyLimitCheck())->handle($request);

        if ($this->limit_error) {
            return response()->json(['limit_error' => $this->limit_error ]);
        }

        return $next($request);
    }
}
