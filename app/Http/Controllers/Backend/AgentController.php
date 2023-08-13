<?php

namespace App\Http\Controllers\Backend;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Support\Str;
use App\Models\AgentDeposit;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\AgentWithdraw;
use App\Models\ThreeLuckyDraw;
use Yajra\DataTables\DataTables;
use App\Models\AgentPaymentReport;
use App\Http\Controllers\Controller;
use App\Models\UserPaymentReport;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        // $data = Agent::latest()->get();
        // return view('backend.admin.agents.index', compact('data'));

        if ($request->ajax()) {
            $query = Agent::select('*');

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('name', function ($agent) {
                        return "
                            <a href='/admin/agent-payment/report/$agent->id' > $agent->name </a>
                        ";
                    })
                    ->addColumn('created_at', function ($agent) {
                        return date("F j, Y, g:i A", strtotime($agent->created_at));
                    })
                    ->addColumn('action', function ($agent) {
                        if( is_admin() ){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$agent->id.'" data-original-title="Edit" class="edit btn btn-info mb-1 editAgent">Edit</a>';
                            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$agent->id.'" data-original-title="Delete" class="btn btn-danger deleteAgent">Delete</a>';
                            return $btn;
                        }
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->orWhere('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['name', 'status','action'])
                    ->make(true);
        }

        return view('backend.admin.agents.index');
    }

    public function create()
    {
        $two_lucky_draws = DB::table('agents')
                   ->join('two_lucky_draws', 'two_lucky_draws.agent_id', '=', 'agents.id')
                   ->selectRaw('SUM(two_lucky_draws.amount) as amount, DATE(two_lucky_draws.created_at) day, agents.name as name, agents.referral_code as referral_code')
                   ->groupBy('day', 'name', 'referral_code')
                   ->get();

        // return $two_lucky_draws;
        return view('backend.agent.agent-two', compact('two_lucky_draws'));
    }

    public function threeLuckyDraw()
    {
        $three_lucky_draws = DB::table('agents')
                   ->join('three_lucky_draws', 'three_lucky_draws.agent_id', '=', 'agents.id')
                   ->selectRaw('SUM(three_lucky_draws.amount) as amount, DATE(three_lucky_draws.created_at) day, agents.name as name, agents.referral_code as referral_code')
                   ->groupBy('day', 'name', 'referral_code')
                   ->get();

        // return $three_lucky_draws;
        return view('backend.agent.agent-three', compact('three_lucky_draws'));
    }

    public function footballLuckyDraw()
    {
        $data = DB::table('agents')
                   ->join('football_bets', 'football_bets.agent_id', '=', 'agents.id')
                   ->selectRaw('SUM(football_bets.amount) as amount, DATE(football_bets.created_at) day, agents.name as name, agents.referral_code as referral_code')
                   ->groupBy('day', 'name', 'referral_code')
                   ->get();
                //    return $data;
        return view('backend.agent.agent-football', compact('data'));
    }

    public function agentPercentage(Request $request)
    {
        // return "some";
        $two_percentages = DB::table('agents')
                   ->join('two_lucky_draws', 'two_lucky_draws.agent_id', '=', 'agents.id')
                   ->selectRaw('SUM(two_lucky_draws.amount) as amount, agents.name as name, agents.id as id')
                   ->groupBy('name', 'id')
                   ->get();

        $three_percentages = DB::table('agents')
                       ->join('three_lucky_draws', 'three_lucky_draws.agent_id', '=', 'agents.id')
                       ->selectRaw('SUM(three_lucky_draws.amount) as amount, agents.name as name, agents.id as id')
                       ->groupBy('name', 'id')
                       ->get();

        $football_percentages = DB::table('agents')
                       ->join('football_bets', 'football_bets.agent_id', '=', 'agents.id')
                       ->selectRaw('SUM(football_bets.amount) as amount, agents.name as name, agents.id as id')
                       ->groupBy('name', 'id')
                       ->get();

        $collection = collect([$two_percentages, $three_percentages, $football_percentages])->flatten()->all();
        $result = array();

        foreach ($collection as $k => $v) {
            $name = $v->name;
            $result[$v->id][$name][] = $v->amount;
        }
        // return $result;
        $percentages = array();
        foreach ($result as $key => $value) {
            foreach ($value as $na => $va) {
                $percentages[] = array( 'id' => $key ,'name' => $na, 'amount' => array_sum($va));
            }
        }

        // return $percentages;

        return view('backend.agent.agent-percentage', compact('percentages'));
    }

    public function updatePercentage(Request $request)
    {
        // return $request->all();
        $percentage = Agent::find($request->agent_id)->value('percentage');
        $amount = ($percentage / 100) * $request->amount;
        Agent::find($request->agent_id)->update(['amount' => $amount]);
        return redirect()->route('agents.index')->with('success', 'Agent percentage added successfully!');
    }

    public function store(Request $request)
    {
        // return $request->all();

        if (is_null($request->agent_id)) {
            $request->validate([
                'name' => 'required|string|max:255',
                // 'phone' => 'required|phone:MM|unique:agents',
                'password' => 'required|string|min:8|same:confirm-password',
            ]);
            $random = Str::random(8);
            $password = Hash::make($request->password);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                // 'phone' => 'required|phone:MM|unique:agents,phone,'.$request->agent_id,
                'phone' => 'required|unique:agents,phone,'.$request->agent_id,
                'password' => 'nullable|string|min:8|same:confirm-password',
            ]);
            $random = Agent::where('id', $request->agent_id)->value('referral_code');

            if ($request->password) {
                $password = Hash::make($request->password);
            } else {
                $password = Agent::where('id', $request->agent_id)->value('password');
            }
        }
        Agent::updateOrCreate([
            'id'   => $request->agent_id,
        ], [
            'name'     => $request->name,
            'phone' => $request->phone,
            'amount' => $request->amount ?: 0,
            'percentage' => $request->percentage,
            'referral_code' => $random,
            'password' => $password
        ]);

        return response()->json(['success'=>'Agent saved successfully.']);
    }

    public function edit($id)
    {
        $agent = Agent::find($id);
        return response()->json($agent);
    }

    public function destroy($id)
    {
        Agent::find($id)->delete();
        return response()->json(['success'=>'Agent deleted successfully.']);
    }

    // Payment Report
    public function payment_report(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $query = AgentPaymentReport::where('agent_id', $agent->id)->with('agent')->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
            } else {
                $query = AgentPaymentReport::where('agent_id', $agent->id)->with('agent')->latest();
            }

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('agent', function ($data) {
                        return $data->agent?->name;
                    })
                    ->addColumn('deposit', function ($data) {
                        $count = UserPaymentReport::getDepositCount($data->agent_id, $data->created_at);
                        return "$data->deposit ($count)";
                    })
                    ->addColumn('withdraw', function ($data) {
                        $count = UserPaymentReport::getWithdrawCount($data->agent_id, $data->created_at);
                        return "$data->withdraw ($count)";
                    })
                    ->addColumn('net', function ($data) {
                        return $data->deposit - $data->withdraw;
                    })
                    ->addColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-m-Y');
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('agent', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['agent'])
                    ->make(true);
        }

        return view("backend.report.payment", compact('id'));
    }
}
