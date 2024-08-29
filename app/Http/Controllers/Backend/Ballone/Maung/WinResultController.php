<?php

namespace App\Http\Controllers\Backend\Ballone\Maung;

use App\Models\FootballBet;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Ballone\Maung\WinRecordService;
use App\Services\Ballone\Maung\WinResultService;

class WinResultController extends Controller
{
    protected $round;

    public function __construct()
    {
        $this->round = Cache::remember('round', 60 * 60, function () {
            return DB::table("football_matches")->latest('round')->first()?->round;
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $filter = $request->only(['agent_id', 'user_id', 'type', 'done']);

            $query = (new WinResultService($filter))->execute($this->round);

            return Datatables::of($query)

                ->with('report', function () use ($query) {

                    return  $query->clone()->selectRaw(
                        '
                            MAX(round) as round,
                            COUNT(CASE when is_done = 0 then 1 end) as no_done ,
                            COUNT(*) as count ,
                            IFNULL(SUM(net_amount), 0) as win
                        '
                    )->first()
                    ->toArray();
                })

                ->addIndexColumn()

                ->addColumn('agent_name', function ($q) {
                    return $q->agent->name;
                })

                ->addColumn('user_id', function ($q) {
                    return $q->user->user_id;
                })

                ->addColumn('total_count', function ($q) {
                    return $q->maung->count;
                })

                ->addColumn('betting_time', function ($q) {
                    return $q->created_at->format('d-m-Y h:i A');
                })

                ->addColumn('betting_amount', function ($q) {
                    return number_format($q->amount);
                })

                ->addColumn('win_amount', function ($q) {
                    return number_format($q->net_amount);
                })

                ->addColumn('result', function ($q) {
                    return $q->result;
                })

                ->addColumn('result_time', function ($q) {
                    return $q->updated_at->format('d-m-Y h:i A');
                })

                ->addColumn('done', function ($q) {
                    return $q->is_done ? 'Success' : '' ;
                })

                ->rawColumns([ 'done' ])

                ->setRowClass(function ($q) {
                    return ($q->is_done == 0) ? 'bg-error' : '';
                })

                ->make(true);
        }

        return redirect('/admin/ballone/maung');

    }

    public function action(WinRecordService $winRecordService)
    {
        $winRecordService->execute($this->round);

        return view("backend.admin.ballone.match.maung.win_result");

    }
}
