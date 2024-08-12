<?php

namespace App\Http\Controllers\Record;

use App\Models\UserLog;
use App\Models\WinRecord;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Repository\WinRecordRepository;
use App\Services\Record\WinRecordService;
use PhpParser\Node\Stmt\Catch_;

class WinController extends Controller
{
    protected $search;

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = [
                'filter' => $request->only(['agent_id', 'user_id', 'start_date', 'end_date'])
            ];

            $query = (new WinRecordRepository($data))->execute();

            return Datatables::of($query)

                ->addIndexColumn()

                ->addColumn('user_id', function ($q) {
                    return $q->user_ids;
                })

                ->addColumn('amount', function ($q) {
                    return number_format($q->amount);
                })

                ->addColumn('time', function ($q) {
                    return dateTimeFormat($q->created_at);
                })

                ->addColumn("action", function($q){
                    return "
                        <a href='#' class='btn btn-danger btn-sm delete_btn' data-route='/admin/win-record/delete/{$q->id}'> Delete </a>
                    ";
                })

                ->rawColumns([ 'action' ])

                ->make(true);
        }

        return view("backend.record.win");
    }

    public function destroy($id, WinRecordService $winRecordService)
    {
        $record = WinRecord::with('user')->find($id);

        if(!$record){
            return response()->json([
                'error'   => true,
                'message' => 'No Record Found'
            ]);
        }

        try{

            $winRecordService->executeDelete($record);

            return response()->json([
                'success' => true,
                'message' => 'success'
            ]);

        }catch(\Exception $exception){

            return response()->json([
                'error' => true,
                'message' => $exception->getMessage()
            ]);
        }

    }
}
