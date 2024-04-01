<?php

namespace App\Http\Controllers\Backend\Report;

use Carbon\Carbon;
use App\Models\Agent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AgentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $agents = Agent::select('id', 'name')->get();

        if ($request->ajax()) {

            $table = ($request->agent_id)
                ? DB::table("agent_payment_reports")->whereIn("agent_id", $request->agent_id)
                : DB::table("agent_payment_all_reports");

            $query = $table

                ->when(!empty($request->from_date), function ($q) use ($request) {
                    return $q->whereBetween('created_at', [$request->from_date, $request->to_date]);
                })

                ->select(

                    DB::raw("sum(deposit) as deposit"),

                    DB::raw("sum(withdraw) as withdraw"),

                    DB::raw("sum(deposit) - sum(withdraw) as net_amount"),

                    DB::raw("DATE(created_at) as date")
                )

                ->groupBy(
                    DB::raw("(DATE(created_at))")
                )

                ->orderBy(DB::raw("(DATE(created_at))"), 'desc');

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('deposit', function ($q) {
                    return "<p class='deposit_amount text-right mb-0' data-amount='{$q->deposit}'>" . number_format($q->deposit) . "</p>";
                })

                ->addColumn('withdraw', function ($q) {
                    return "<p class='withdraw_amount text-right mb-0' data-amount='{$q->withdraw}'>" . number_format($q->withdraw) . "</p>";
                })

                ->addColumn('net', function ($q) {
                    return "<p class='net_amount text-right mb-0' data-amount='{$q->net_amount}'>" . number_format($q->net_amount) . "</p>";
                })

                ->addColumn('created_at', function ($data) {
                    return "<p class='text-center mb-0'>" . Carbon::parse($data->date)->format("d-m-Y") . "</p>";
                })

                ->filter(function ($instance) use ($request) {
                })

                ->rawColumns(['deposit', 'withdraw', 'net', 'created_at'])
                ->make(true);
        }

        return view("backend.report.agent-payments", compact('agents'));
    }

    public function total_payments(Request $request)
    {
        $table = ($request->agent_id)
            ? DB::table("agent_payment_reports")->whereIn("agent_id", $request->agent_id)
            : DB::table("agent_payment_all_reports");

        return $table

            ->when(!empty($request->from_date), function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->from_date, $request->to_date]);
            })

            ->select(

                DB::raw("sum(deposit) as deposit"),

                DB::raw("sum(withdraw) as withdraw"),

                DB::raw("sum(deposit) - sum(withdraw) as net_amount")
            )

            ->first();
    }

    // Payment Report Per Agent

    public function payment_report(Request $request, $id)
    {
        if ($request->ajax()) {

            $query = DB::table("user_payment_reports")

                ->join("agents", "agents.id", "=", "user_payment_reports.agent_id")

                ->where('user_payment_reports.agent_id', $id)

                ->when(!empty($request->from_date), function ($q) use ($request) {
                    return $q->whereBetween('user_payment_reports.created_at', [$request->from_date, $request->to_date]);
                })

                ->select(
                    'agents.name as agent',

                    DB::raw("sum(user_payment_reports.deposit) as deposit"),
                    DB::raw("count(case when deposit != 0 then 1 end) as deposit_count"),

                    DB::raw("sum(user_payment_reports.withdraw) as withdraw"),
                    DB::raw("count(case when withdraw != 0 then 1 end) as withdraw_count"),

                    DB::raw("DATE(user_payment_reports.created_at) as date")
                )

                ->groupBy(
                    'name',
                    DB::raw("(DATE(user_payment_reports.created_at))")
                )

                ->orderBy(DB::raw("(DATE(user_payment_reports.created_at))"), 'desc');

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('agent', function ($data) {
                    return $data->agent;
                })

                ->addColumn('deposit', function ($q) {
                    $amount = number_format($q->deposit);
                    return "$amount ($q->deposit_count)";
                })

                ->addColumn('withdraw', function ($q) {
                    $amount = number_format($q->withdraw);
                    return "$amount ($q->withdraw_count)";
                })

                ->addColumn('net', function ($data) {
                    return number_format($data->deposit - $data->withdraw);
                })

                ->addColumn('created_at', function ($data) {
                    return Carbon::parse($data->date)->format("d-m-Y");
                })

                ->filter(function ($instance) use ($request) {
                })

                ->make(true);
        }

        return view("backend.report.payment", compact('id'));
    }
}
