<?php

namespace App\Http\Controllers\Api\Games;

use App\Http\Controllers\Api\ResponseController;
use Carbon\Carbon;
use App\Models\Enabled;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Illuminate\Support\Facades\DB;
use App\Services\Ballone\BetService;
use Illuminate\Support\Facades\Auth;
use App\Services\Ballone\BodyLimitCheck;
use App\Services\Ballone\MaungValidation;
use Illuminate\Database\Eloquent\Builder;

class FootballController extends ResponseController
{
    public function bodyMatch()
    {
        $min = Auth::user()->body_min_amount();
        $data = [];
        $current_round = DB::table("football_matches")->latest('round')->first()?->round;
        if (Enabled::first()?->body_status) {
            $data = FootballMatch::has('bodyfees')
                ->with('bodyfees', 'bodyLimit')
                ->whereHas('matchStatus', function (Builder $query) {
                    $query->where('all_close', 0)->where('body_refund', 0);
                })
                ->where('calculate_body', 0)
                ->where('date_time', '>', now())
                ->where('type', 1)
                ->where('round', $current_round)
                ->orderBy('home_no', 'asc')
                ->get()
                ->groupBy(fn($i) => $i->league->name);
        }

        return $this->successResponse($data, 'Body Match Fetched successfully', 200);
    }

    public function maungMatch()
    {
        $min = Auth::user()->maung_min_amount();
        $data = [];
        $current_round = DB::table("football_matches")->latest('round')->first()?->round;

        if (Enabled::first()?->maung_status) {
            $data = FootballMatch::has('maungfees')
                ->with('maungfees')
                ->whereHas('matchStatus', function (Builder $query) {
                    $query->where('all_close', 0)->where('maung_refund', 0);
                })
                ->where('calculate_maung', 0)
                ->where('date_time', '>', now())
                ->where('type', 1)
                ->where('round', $current_round)
                ->orderBy('home_no', 'asc')
                ->get()
                ->groupBy(fn($i) => $i->league->name);
        }

        return $this->successResponse($data, 'Maung Match Fetched successfully', 200);
    }

    public function bodyStore(Request $request, BetService $betService)
    {
        if ($error = (new BodyLimitCheck())->handle($request)) {
            return $this->errorResponse("Sonething Went Wrong", 'Limit Error', 400);
        }

        try {
            $betService->bodyStore($request);
            return $this->successResponse([], 'Body Stored successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Error Occurred', 500);
        }
    }

    public function maungStore(Request $request, BetService $betService)
    {
        try {
            (new MaungValidation())->handle($request);
            $betService->maungStore($request);
            return $this->successResponse([], 'Maung Stored successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Error Occurred', 500);
        }
    }

    public function historyMatch()
    {
        $data = FootballBet::where('user_id', auth()->id())->status(0)->latest()->get();
        return $this->successResponse($data, 'History Match Fetched successfully', 200);
    }

    public function allHistoryMatch(Request $request)
    {
        $data = FootballBet::with('body.match', 'body.fees', 'maung.teams.match', 'maung.teams.fees', 'betting_record:id,win_amount')
            ->where('user_id', auth()->id())
            ->filterByDate($request)
            ->orderBy('id', 'desc')
            ->get();

        return $this->successResponse($data, 'All History Match Fetched successfully', 200);
    }

    public function resultMatch()
    {
        $today = FootballMatch::query()
            ->with(['matchStatus'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('home_no', 'asc')
            ->get()
            ->groupBy(fn($i) => $i->league->name);

        $yesterday = FootballMatch::query()
            ->with(['matchStatus'])
            ->whereDate('created_at', Carbon::yesterday())
            ->orderBy('home_no', 'asc')
            ->get()
            ->groupBy(fn($i) => $i->league->name);

        return $this->successResponse(['today' => $today, 'yesterday' => $yesterday], 'Result Match Fetched successfully', 200);
    }
}
