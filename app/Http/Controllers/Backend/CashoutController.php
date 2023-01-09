<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Cashout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CashoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Cashout::with('provider', 'user')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($cashout) {
                        return '<label class="badge badge-info badge-pill">'.$cashout->provider->name.'</label>';
                    })
                    ->addColumn('user', function ($cashout) {
                        return '<label class="badge badge-info badge-pill">'.$cashout->user?->name.'</label>';
                    })
                    // ->addColumn('balance', function ($cashout) {
                    //     return '<label class="badge badge-success badge-pill">'.$cashout->user->amount.' MMK</label>';
                    // })
                    ->addColumn('amount', function ($cashout) {
                        return '<label class="badge badge-primary badge-pill">'.$cashout->amount.' MMK</label>';
                    })
                    // ->addColumn('status', function ($cashout) {
                    //     if ($cashout->status == "Completed") {
                    //         return  '<label class="badge badge-success badge-pill">Completed</label>';
                    //     } else {
                    //         return '<input type="checkbox" data-id="'.$cashout->id.'" name="status" class="js-switch" uncheck>';
                    //     }
                    // })
                    ->addColumn('status', function ($cashout) {
                        if ($cashout->status == "Approved") {
                            return  '<label class="badge badge-success badge-pill">Approved</label>';
                        } elseif ($cashout->status == "Rejected") {
                            return  '<label class="badge badge-danger badge-pill">Rejected</label>';
                        } else {
                            return '<form id="editable-form" class="editable-form">
                                        <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                                <a href="#" class="payment_status" data-name="status" data-type="select" data-pk="'.$cashout->id.'"
                                                    data-title="Select Payment Status">'. $cashout->status .'
                                                </a>
                                            </div>
                                        </div>
                                    </form>';
                        }
                    })
                    ->addColumn('created_at', function ($cashout) {
                        return date("F j, Y, g:i A", strtotime($cashout->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('provider', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('user', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['provider','user','balance','amount','status'])
                    ->make(true);
        }
        return view('backend.cashouts.index');
    }

    public function changeTransferStatus(Request $request)
    {
        // $cashout = Cashout::find($request->cashout_id);
        // $cashout->status = $request->status;
        // $cashout->save();

        // if ($request->status == "Completed") {
        //     $user = User::find($cashout->user_id);
        //     $user->update(['amount'=> $user->amount - $cashout->amount]);
        // }

        // return response()->json(['message' => 'Withdraws status changed successfully.']);

        
        // $cashout->status = $request->status;
        // $cashout->save();

        if ($request->value == "Approved") {
            $cashout = Cashout::find($request->pk);
            $user = User::find($cashout->user_id);
            $user->update(['amount'=> $user->amount - $cashout->amount]);
        }
        Cashout::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['message' => 'Withdrawal status changed successfully.']);
    }
}
