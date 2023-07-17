<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Agent;
use App\Models\TwoDigit;
use App\Models\TwoWinner;
use App\Models\LotteryTime;
use App\Models\TwoLuckyDraw;
use Illuminate\Http\Request;
use App\Models\TwoLuckyNumber;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TwoDigitCompensation;
use App\Http\Requests\TwoLuckyNumberRequest;

class TwoLuckyNumberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TwoLuckyNumber::with('two_digit', 'lottery_time')->orderBy('date', 'desc');
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('number', function ($number) {
                        return '<label class="badge badge-success badge-pill">'.$number->two_digit->number.'</label>';
                    })
                    ->addColumn('lottery_time', function ($number) {
                        return '<label class="badge badge-primary badge-pill">'.$number->lottery_time->time.'</label>';
                    })
                    ->addColumn('status', function ($number) {
                        if ($number->status == "Approved") {
                            return  '<label class="badge badge-info badge-pill">Approved</label>';
                        } else {
                            return '<form id="editable-form" class="editable-form">
                                        <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                                <a href="#" class="two_lucky_no_status" data-name="status" data-type="select" data-pk="'.$number->id.'">'. $number->status .'
                                                </a>
                                            </div>
                                        </div>
                                    </form>';
                        }
                    })
                    ->addColumn('date', function ($number) {
                        return date("F j, Y", strtotime($number->date));
                    })
                    ->addColumn('created_at', function ($number) {
                        return date("F j, Y, g:i A", strtotime($number->created_at));
                    })
                    ->addColumn('action', function ($number) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Edit" class="edit btn btn-warning editNumber">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$number->id.'" data-original-title="Delete" class="btn btn-danger deleteNumber">Delete</a>';
                        if ($number->status == "Approved") {
                            return '';
                        }
                        return $btn;
                    })
                    // ->filter(function ($instance) use ($request) {
                    //     if (!empty($request->get('search'))) {
                    //         $instance->where(function ($w) use ($request) {
                    //             $search = $request->get('search');
                    //             $w->orWhere('lottery_time', 'LIKE', "%$search%");
                    //         });
                    //     }
                    // })
                    ->rawColumns(['number','lottery_time','status','action'])
                    ->make(true);
        }

        $times_one = LotteryTime::where('type', 0)->get();

        return view('backend.admin.lucky_numbers.2digits', compact('times_one'));
    }

    public function store(TwoLuckyNumberRequest $request)
    {
        $two_digit_id = TwoDigit::where('number', $request->twodigit_number)->first();

        $lottery_time = ($request->type == 1) ? $request->lottery_time_2 : $request->lottery_time_1;

        $data = TwoLuckyNumber::whereDate('date', $request->date)
                            ->where('lottery_time_id', $lottery_time)
                            ->first();

        if (!$request->twodigit_id && $data) {
            return response()->json(['error' => 'Lucky number is already added.']);
        }

        TwoLuckyNumber::updateOrCreate([
            'id'   => $request->twodigit_id,
        ], [
            'two_digit_id'     => $two_digit_id['id'],
            'date' => $request->date,
            'lottery_time_id' => $lottery_time,
            'type' => 0
        ]);

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
        $number = TwoLuckyNumber::whereId($id)->with('two_digit')->first();
        return response()->json($number);
    }

    public function destroy($id)
    {
        TwoLuckyNumber::find($id)->delete();
        return response()->json(['success'=>'Lucky number deleted successfully.']);
    }

    public function UpdateByAjax(Request $request)
    {
        // return $request->all();
        if ($request->value == "Approved") {
            $data = TwoLuckyNumber::find($request->pk);
            // $yesterday = date("Y-m-d 16:30:00", strtotime("yesterday"));
            // return $data->lottery_time;
            $date = $data->date;
            $two_lucky_draw_id = TwoLuckyDraw::where([
                                    ['created_at','>=', $date ],
                                    ['lottery_time_id','=', $data->lottery_time_id],
                                    ['two_digit_id','=',$data->two_digit_id],
                                ])->get();


            // return $two_lucky_draw_id;

            foreach ($two_lucky_draw_id as $key => $value) {
                TwoWinner::create([
                    'two_lucky_number_id' => $data->id,
                    'two_lucky_draw_id' => $value->id
                ]);
            }

            $grouped = TwoLuckyDraw::where([
                        ['created_at','>=', $date ],
                        ['lottery_time_id','=',$data->lottery_time_id],
                        ['two_digit_id','=',$data->two_digit_id],
                    ])->selectRaw('SUM(amount) as amount, user_id as user_id')->groupBy('user_id')->get();


            $za = TwoDigitCompensation::first();
            foreach ($grouped as $key => $value) {
                // $current_amount = User::find($value->user_id);
                // $balance = $current_amount['amount'] + $value->amount * $za->compensate;
                // User::find($value->user_id)->update(['amount'=> $balance]);
                $amount = $value->amount * $za->compensate;
                if ($value->user_id) {
                    User::find($value->user_id)->increment('amount', $amount);
                } else {
                    Agent::find($value->agent_id)->increment('amount', $amount);
                }
            }
        }
        TwoLuckyNumber::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['message' => 'Lucky number status changed successfully.']);
    }
}
