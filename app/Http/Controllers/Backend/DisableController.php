<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Disable;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class DisableController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Disable::get('*');

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('updated_at', function ($disable) {
                        return date("F j, Y, g:i A", strtotime($disable->updated_at));
                    })
                    ->addColumn('datetime', function ($disable) {
                        return date("F j, Y, g:i A", strtotime($disable->datetime));
                    })
                    ->addColumn('action', function ($disable) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$disable->id.'" data-original-title="Edit" class="btn btn-danger editDateTime">Edit</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('backend.admin.disable.index');
    }

    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'datetime' => 'required|date_format:Y-m-d H:i',
        ]);

        // $dt = Carbon::parse($request->datetime)->format('Y-m-d H:i');
        // return $dt;
        Disable::find(1)->update(['datetime' => $request->datetime]);
        return response()->json(['success'=>'User saved successfully.']);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $disable = Disable::find($id);
        return response()->json($disable);
    }
}
