<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\League;
use App\Models\Enabled;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\FootballMaungFee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FootballBodyLimitGroup;
use App\Services\Ballone\FeesValidation;
use App\Services\Ballone\MaungFeesService;

class MaungFeesController extends Controller
{
    public function index(Request $request)
    {
        $data = FootballMaungFee::with(['match' => function ($q) {
            $q->withCount('bodies', 'maungs');
        }])
            ->join('football_matches', 'football_matches.id', '=', 'football_maung_fees.match_id')
            ->join('football_maung_fee_results', 'football_maung_fee_results.fee_id', '=', 'football_maung_fees.id')
            ->join('admins', 'admins.id', '=', 'football_maung_fees.by')
            ->select('football_maung_fees.*', 'football_maung_fee_results.*', 'admins.name as by_user')
            ->where('football_maung_fees.created_at', '>=', now()->subMonth(6))
            ->orderBy('football_matches.round', 'desc')
            ->orderBy('football_matches.home_no', 'asc')
            ->orderBy('football_maung_fees.created_at', 'desc')
            ->paginate(15);

        $request->session()->forget(['prev_route', 'refresh']);

        return view('backend.admin.ballone.match.maung.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {

            (new FeesValidation())->handle($request);

            $match = FootballMatch::find($request->match_id);

            FootballMaungFee::where('match_id', $match->id)->update(['status' => 0]);

            $maungFees = FootballMaungFee::create([
                'match_id' => $match->id,
                'body'     => ($request->home_body) ?? $request->away_body,
                'goals'    => $request->goals,
                'up_team'  => ($request->home_body) ? 1 : 2,
                'by'       => Auth::id()
            ]);

            $maungFees->result()->create();

            return response()->json(['success' => 'Match saved successfully.']);

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function edit($id)
    {
        $match = FootballMatch::with('maungFees', 'home', 'away')->find($id);
        return response()->json($match);
    }

    //

    public function add()
    {
        $leagues = League::pluck('name', 'id');

        $groups = FootballBodyLimitGroup::orderBy("max_amount")->get();

        $round = FootballMatch::orderBy('round', 'desc')->first()?->round;

        return view("backend.admin.ballone.match.maung.create", compact('leagues', 'round', 'groups'));
    }

    public function create(Request $request)
    {
        // return $request->all();

        $validated = $request->validate([
            'round' => 'required',
            'home_no' => 'required|array',
            'away_no' => 'required|array',
            'league_id' => 'required',
            'date' => 'required|array',
            'time' => 'required|array',
            'home_id' => 'required|array',
            'away_id' => 'required|array',
            'home_body' => 'required|array',
            'away_body' => 'required|array',
            'goals' => 'required|array',
            'other' => 'nullable',
            'limit_group_id' => 'required|array'
        ]);

        try {

            (new FeesValidation())->multipleHandle($request);

            (new MaungFeesService())->executeAdd($validated);

            return response()->json(['url' => '/admin/ballone/maung']);

        } catch (\Exception $exception) {

            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function maungFeesEnable()
    {
        $enable = Enabled::first();

        $enable->update(['maung_status' => !$enable->maung_status]);

        return back()->with('success', 'success');
    }
}
