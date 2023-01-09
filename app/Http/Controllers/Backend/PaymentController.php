<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Payment::with('user', 'provider')->latest();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('provider', function ($payment) {
                        return '<span>'.$payment->provider->name.
                        ' ( '. $payment->provider->phone_number .' ) '.
                        '</span>';
                    })
                    ->addColumn('user', function ($payment) {
                        return '<label class="badge badge-info badge-pill">'.$payment->user?->name.'</label>';
                    })
                    ->addColumn('amount', function ($payment) {
                        return '<label class="badge badge-primary badge-pill">'.$payment->amount.' MMK</label>';
                    })
                    ->addColumn('transation_ss', function ($payment) {
                        return '<img src='.$payment->transation_ss.'>';
                    })
                    ->addColumn('status', function ($payment) {
                        if ($payment->status == "Approved") {
                            return  '<label class="badge badge-success badge-pill">Approved</label>';
                        } elseif ($payment->status == "Rejected") {
                            return  '<label class="badge badge-danger badge-pill">Rejected</label>';
                        } else {
                            return '<form id="editable-form" class="editable-form">
                                        <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                                <a href="#" class="payment_status" data-name="status" data-type="select" data-pk="'.$payment->id.'"
                                                    data-title="Select Payment Status">'. $payment->status .'
                                                </a>
                                            </div>
                                        </div>
                                    </form>';
                        }
                    })
                    ->addColumn('created_at', function ($payment) {
                        return date("F j, Y, g:i A", strtotime($payment->created_at));
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->whereHas('provider', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('transation_no', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            })
                            ->orwhereHas('user', function ($w) use ($request) {
                                $search = $request->get('search');
                                $w->where('name', 'LIKE', "%$search%");
                                $w->orWhere('transation_no', 'LIKE', "%$search%");
                                $w->orWhere('phone', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['amount','status','user','provider','transation_ss'])
                    ->make(true);
        }
        return view('backend.user_payments.index');
    }

    public function UpdateByAjax(Request $request)
    {
        if ($request->value == "Approved") {
            $payment = Payment::find($request->pk);
            $current_amount = User::find($payment->user_id);
            User::find($payment->user_id)->update(['amount'=> $current_amount['amount'] + $payment->amount]);
        }
        Payment::find($request->pk)->update([$request->name => $request->value]);
        return response()->json(['message' => 'Payment status changed successfully.']);
    }
}
