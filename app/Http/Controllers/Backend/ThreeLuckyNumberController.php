<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ThreeDigit;
use App\Models\ThreeWinner;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use App\Services\RecordService;
use App\Models\ThreeLuckyNumber;
use App\Services\UserLogService;
use Yajra\DataTables\DataTables;
use App\Models\ThreeDigitSetting;
use App\Http\Controllers\Controller;
use App\Services\ThreeDigit\LuckyNumberService;

class ThreeLuckyNumberController extends Controller
{
    public function index(Request $request)
    {
        $threedigit_numbers = ThreeDigit::select('id', 'number')->get();

        if ($request->ajax()) {
            $query = ThreeLuckyNumber::with('three_digit')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('number', function ($number) {
                        return $number->three_digit?->number;
                    })
                    ->addColumn('status', function ($number) {
                        if( !$number->three_digit) return "";

                        if ($number->status == "Approved") {
                            return  '<label class="badge badge-success text-white badge-pill">Approved</label>';
                        } else {
                            return '<form id="editable-form" class="editable-form">
                                        <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                                <a href="#" class="three_lucky_no_status" data-name="status" data-type="select" data-pk="'.$number->id.'">'. $number->status .'
                                                </a>
                                            </div>
                                        </div>
                                    </form>';
                        }
                    })
                    ->addColumn('date', function ($number) {
                        return date("F j, Y", strtotime($number->lottery->date));
                    })
                    ->addColumn('action', function ($number) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Edit" class="edit btn btn-warning editNumber">Edit</a>';
                        // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Delete" class="btn btn-danger deleteNumber">Delete</a>';

                        if ($number->status == "Approved") {
                            return '';
                        }

                        return $btn;

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->orWhere('status', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['number','status','action'])
                    ->make(true);
        }
        return view('backend.admin.lucky_numbers.3digits', compact('threedigit_numbers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'threedigit_number' => 'required',
            // 'votes' => 'required',
        ]);

        // if( !$request->threedigit_id){
        //     $check = ThreeLuckyNumber::where('status', '!=', 'Approved')->get();

        //     if($check){
        //         return response()->json(['error' => 'already added']);
        //     }
        // }

        // ThreeLuckyNumber::updateOrCreate([
        //     'id'   => $request->threedigit_id,
        // ], [
        //     'three_digit_id' =>  $request->threedigit_number,
        //     'votes' => 0,
        //     'date' => $request->date
        // ]);

        ThreeDigitSetting::find(1)->lucky_number()
        ->update([ 'three_digit_id' =>  $request->threedigit_number ]);

        return response()->json(['success'=>'Lucky number saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $number = ThreeLuckyNumber::find($id);
        return response()->json($number);
    }

    public function destroy($id)
    {
        // ThreeLuckyNumber::find($id)->delete();
        // return response()->json(['success'=>'Lucky number deleted successfully.']);
    }

    public function UpdateByAjax(Request $request)
    {
        $data = ThreeLuckyNumber::find($request->pk);

        if ($request->value == "Approved") {

            (new LuckyNumberService())->handle($data);

            $data->update([ 'status' => "Approved" ]);
        }

        // $data->update([$request->name => $request->value]);

        return response()->json(['message' => 'Lucky number status changed successfully.']);
    }
}
