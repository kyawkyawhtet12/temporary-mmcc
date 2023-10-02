<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\ThreeDigit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\ThreeDigitSetting;
use App\Http\Controllers\Controller;
use App\Services\ThreeDigit\LuckyNumberService;

class ThreeDigitLuckySettingController extends Controller
{
    public function index(Request $request)
    {
        $threedigit_numbers = ThreeDigit::select('id', 'number')->get();

        if ($request->ajax()) {

            $query = ThreeDigitSetting::with('lucky_number')->latest('id')->get();

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('date', function($data){
                        return Carbon::parse($data->date)->format('d-m-Y');
                    })
                    ->addColumn('start_time', function ($data) {
                        return Carbon::parse($data->start_time)->format('d-m-Y, g:i A');
                    })
                    ->addColumn('end_time', function ($data) {
                        return Carbon::parse($data->end_time)->format('d-m-Y, g:i A');
                    })
                    ->addColumn('number', function($data){
                        return $data->lucky_number_full;
                    })
                    ->addColumn('action', function ($data) {

                        if( $data->lucky_number->status == "Approved"){
                            return "";
                        }

                        return
                            '<a href="javascript:void(0)" class="btn btn-danger editDateTime" data-id="'.$data->id.'" data-start="'.$data->start_time.'" data-end="'.$data->end_time.'">
                                Edit
                            </a>';

                    })
                    ->addColumn('status', function($data){

                        if(!$data->lucky_number->three_digit){
                            return "";
                        }

                        if( $data->lucky_number->status == "Approved"){
                            return "Done";
                        }

                        return '<a href="javascript:void(0)" class="btn btn-primary mx-2 finish" data-id="'.$data->id.'" data-number="'.$data->lucky_number_full.'">
                            Finish
                        </a>';
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
        }

        return view('backend.admin.3d-lucky-setting.index', compact('threedigit_numbers'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'start_time' => 'required',
            'end_time'   => 'required'
        ]);

        $setting = ThreeDigitSetting::whereStatus(1)->first();

        $setting->update([
            'start_time' => $request->start_time ,
            'end_time'   => $request->end_time,
            'date'       => Carbon::parse($request->end_time)->format('Y-m-d')
        ]);

        $setting->lucky_number()->update([
                    'three_digit_id' =>  $request->threedigit_number
                ]);

        return response()->json(['success'=>'User saved successfully.']);
    }

    public function approve(Request $request)
    {
        $data = ThreeDigitSetting::findOrFail($request->id);

        (new LuckyNumberService())->handle($data);

        // return response()->json(['message' => 'Lucky number status changed successfully.']);
        return back()->with('success','success');
    }
}
