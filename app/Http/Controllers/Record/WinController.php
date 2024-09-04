<?php

namespace App\Http\Controllers\Record;

use App\Models\WinRecord;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMaung;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repository\WinRecordRepository;
use App\Services\Record\WinRecordService;
use App\Services\Ballone\MaungServiceCheck;
use App\Repository\WinRecordCheckRepository;

 class WinController extends Controller
{
    protected $search;
    protected $error;

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
                    $time = dateTimeFormat($q->created_at);

                    $bet_id = request()->input('record_check') ? $q->betting_id : '';

                    return "<p class='mb-2'> $time </p> <span class='text-danger'> $bet_id </span>";
                })

                ->addColumn("action", function($q){
                    if(is_admin()){
                        return "
                            <a href='#' class='btn btn-danger btn-sm delete_btn' data-route='/admin/win-record/delete/{$q->id}'> Delete </a>
                        ";
                    }
                })

                ->rawColumns([ 'action' , 'time' ])

                ->make(true);
        }

        return view("backend.record.win", [ 'duplicate_filter' => false ]);
    }

    public function error_record(Request $request)
    {
        return view("backend.record.win", [ 'duplicate_filter' => true ]);
    }



    public function fix()
    {

        // $groups = WinRecord::whereDate('created_at', today())->where('type', 'Maung')->pluck('betting_id')->unique()->toArray();

        $groups = [
            50580,
            50600,
            50619,
            50662,
            50687,
            50702,
            50744
        ];

        $maungs = FootballMaung::whereIn('maung_group_id', $groups)->get();

        // return $maungs;

        // $service = (new MaungServiceCheck())->execute($maungs);

        // return $service;

        // $bets = FootballBet::whereIn('maung_group_id', $groups)->update([ 'temp_amount' => 0 ]);

        // $bets = FootballBet::whereIn('maung_group_id', $groups)->get();

        // return $bets;



        $groups = FootballBet::whereIn('maung_group_id', $array)->get();

        $win_records = WinRecord::whereIn('betting_id', $array)->where('status', 0)->get();

        return $win_records;

        $error_group = [];

        foreach($bets as $bet)
        {
            $win_amount = $bet->temp_amount - ( $bet->temp_amount * 15/100 );

            if($bet->net_amount != $win_amount){
                $error_group[] = $bet->maung_group_id;
            }

            $bet->update([
                'temp_amount' => $win_amount
            ]);
        }

        return $error_group;
    }

    public function destroy($id, WinRecordService $winRecordService)
    {

        if(!is_admin()){
            return response()->json([
                'error' => true,
                'message' => 'permission denied'
            ]);
        }

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
