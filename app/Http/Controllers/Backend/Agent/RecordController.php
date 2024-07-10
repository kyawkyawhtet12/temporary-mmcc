<?php

namespace App\Http\Controllers\Backend\Agent;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Services\Report\AgentReportService;

class RecordController extends Controller
{
    protected $agentReportService;

    protected $filter;

    public function __construct(AgentReportService $agentReportService)
    {
        $this->agentReportService = $agentReportService;

        $this->filter = [
            'agent_id' => request()->get('agent_id'),
            'start_date' => request()->get('start_date') ?? today()->subMonth(6)->format("Y-m-d"),
            'end_date' => request()->get('end_date') ?? today()->format("Y-m-d")
        ];
    }

    public function index(Request $request, $type)
    {
        if ($request->ajax()) {

            $query = $this->agentReportService
                        ->executeRecord(
                            $this->filter,
                            $type
                        );

            return Datatables::of($query)

                    ->addIndexColumn()

                    ->addColumn('date', function ($q) {
                        return dateFormat($q->date);
                    })

                    ->addColumn('amount', function($q){
                        return number_format($q->amount) . " ( $q->count ) ";
                    })

                    ->make(true);

        }

        return view('backend.agent.record.index', [
            'type'   => $type ,
            'filter' => $this->filter
        ]);
    }

}
