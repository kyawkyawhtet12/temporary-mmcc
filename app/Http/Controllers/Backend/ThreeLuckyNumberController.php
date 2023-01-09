<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ThreeDigit;
use App\Models\ThreeWinner;
use Illuminate\Http\Request;
use App\Models\ThreeLuckyDraw;
use App\Models\ThreeLuckyNumber;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\ThreeDigitCompensation;

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
                        return '<label class="badge badge-success badge-pill">'.$number->three_digit->number.'</label>';
                    })
                    ->addColumn('votes', function ($number) {
                        $votes = unserialize($number->votes);
                        foreach ($votes as $value) {
                            $result[] = $value;
                        }
                        return $result;
                    })
                    ->addColumn('status', function ($number) {
                        if ($number->status == "Approved") {
                            return  '<label class="badge badge-info badge-pill">Approved</label>';
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
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->orWhere('status', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['votes', 'number','status','action'])
                    ->make(true);
        }
        return view('backend.admin.lucky_numbers.3digits', compact('threedigit_numbers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'threedigit_number' => 'required',
            'votes' => 'required',
        ]);
        

        ThreeLuckyNumber::updateOrCreate([
            'id'   => $request->threedigit_id,
        ], [
            'three_digit_id' =>  $request->threedigit_number,
            'votes' => serialize($request->votes),
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
        $number = ThreeLuckyNumber::find($id);
        return response()->json($number);
    }

    public function destroy($id)
    {
        ThreeLuckyNumber::find($id)->delete();
        return response()->json(['success'=>'Lucky number deleted successfully.']);
    }
    
    public function UpdateByAjax(Request $request)
    {
        if ($request->value == "Approved") {
            $data = ThreeLuckyNumber::find($request->pk);
            $ids = [];
            foreach (unserialize($data->votes) as $q) {
                array_push($ids, $q);
            }
            $query = ThreeDigit::whereIn('number', $ids)->pluck('id');

            $votes = ThreeLuckyDraw::where('created_at', '>=', Carbon::now()->subDay('15'))
                                                    ->whereIn('three_digit_id', $query)->get();

            foreach ($votes as $key => $value) {
                ThreeWinner::create([
                    'three_lucky_number_id' => $data->id,
                    'three_lucky_draw_id' => $value->id,
                    'status' => 'Za',
                ]);
            }

            $three_lucky_draw_id = ThreeLuckyDraw::where([
                                            ['created_at','>=',Carbon::now()->subDay('15')],
                                            ['three_digit_id','=',$data->three_digit_id],
                                        ])->get();

            foreach ($three_lucky_draw_id as $key => $value) {
                ThreeWinner::create([
                    'three_lucky_number_id' => $data->id,
                    'three_lucky_draw_id' => $value->id,
                    'status' => 'Full',
                ]);
            }

            $za = ThreeDigitCompensation::first();
            $grouped_votes = ThreeLuckyDraw::where('created_at', '>=', Carbon::now()->subDay('15'))
                                                    ->whereIn('three_digit_id', $query)
                                                    ->selectRaw('SUM(amount) as amount, user_id as user_id, agent_id as agent_id')->groupBy('user_id', 'agent_id')
                                                    ->get();

            foreach ($grouped_votes as $key => $value) {
                // $current_amount = User::find($value->user_id);
                // $balance = $current_amount['amount'] + $value->amount * $za->vote;
                $amount = $value->amount * $za->vote;
                if ($value->user_id) {
                    User::find($value->user_id)->increment('amount', $amount);
                } else {
                    Agent::find($value->agent_id)->increment('amount', $amount);
                }
            }

            $grouped = ThreeLuckyDraw::where([
                            ['created_at','>=',Carbon::now()->subDay('15')],
                            ['three_digit_id','=',$data->three_digit_id],
                        ])->selectRaw('SUM(amount) as amount, user_id as user_id, agent_id as agent_id')
                        ->groupBy('user_id', 'agent_id')->get();
            
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
        ThreeLuckyNumber::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['message' => 'Lucky number status changed successfully.']);
    }
}
