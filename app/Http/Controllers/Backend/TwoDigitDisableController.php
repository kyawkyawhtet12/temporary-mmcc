<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\LotteryTime;
use Illuminate\Http\Request;
use App\Models\TwoDigitLimit;
use App\Http\Controllers\Controller;
use App\Http\Requests\TwoDigitLimitAddRequest;

class TwoDigitDisableController extends Controller
{
    // Per Agent
    public function index(Request $request)
    {
        $agents = Agent::select('id', 'name')->get();
        $times = LotteryTime::select("id", "time")->get();

        $date = $request->date ?? today()->format('Y-m-d');
        $time_id = $request->time_id ?? 1;

        $data = TwoDigitLimit::with("agent")->whereNotNull('agent_id')
            ->whereDate("date", $date)
            ->where('lottery_time_id', $time_id)
            ->when($request->agent_id, function ($query, $agent_id) {
                $query->whereIn('agent_id', $agent_id);
            })
            ->orderBy('status', 'desc')
            ->get()
            ->groupBy('agent.name');

        $filtered = [
            'time_id' => $time_id,
            'date' => $date,
            'data' => $data,
            'agents' => $request->agent_id ?? []
        ];

        return view('backend.admin.2d-close.index', compact('agents', 'times', 'data', 'filtered'));
    }

    public function store(TwoDigitLimitAddRequest $request)
    {
        if ($request->type == 1) {

            foreach ($request->numbers as $number) {
                $limit[intval($number)] = $request->amount ?: 0;
            }

            $status = 1;
        } else {
            $limit = $request->frontNumbers;
            $status = 2;
        }

        foreach ($request->agent_id as $agent_id) {

            foreach ($request->time_id as $time) {

                $close = TwoDigitLimit::firstOrCreate([
                    'agent_id' => $agent_id,
                    'date'     => $request->date,
                    'lottery_time_id'  => $time,
                    'status' => $status
                ], [
                    'number' => json_encode($limit, true)
                ]);

                $new = $limit + json_decode($close->number, true);

                $close->update(
                    [
                        'admin_id' => auth()->id(),
                        'number' => json_encode($new, true)
                    ]
                );
            }
        }

        return back()->with('success', '* successfully added');
    }

    public function destroy($id)
    {

        if ($id == 'all') {
            TwoDigitLimit::whereNotNull('agent_id')->delete();
        } else {

            TwoDigitLimit::find($id)->delete();
        }

        return response()->json('success');
    }

    // For All
    public function disable_all(Request $request)
    {
        $data = TwoDigitLimit::whereNull('agent_id')->first();
        return view('backend.admin.2d-close.all', compact('data'));
    }

    public function store_all(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'numbers' => 'required|array'
        ]);

        foreach ($request->numbers as $number) {
            $limit[intval($number)] = $request->amount ?: 0;
        }

        $close = TwoDigitLimit::firstOrCreate(
            ['agent_id' => NULL],
            ['number' => json_encode($limit, true)]
        );

        $new = $limit + json_decode($close->number, true);

        $close->update(
            [
                'admin_id' => auth()->id(),
                'number'   => json_encode($new, true),
                'amount'   => $request->amount ?: 0
            ]
        );

        return back()->with('success', '* successfully added');
    }

    public function destroy_all($id)
    {
        TwoDigitLimit::whereNull('agent_id')->delete();

        return response()->json('success');
    }
}
