<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\FootballBodySetting;
use App\Http\Controllers\Controller;

class BodySettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FootballBodySetting::all();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Edit" class="editBtn"><i class="fa fa-edit text-inverse m-r-10"></i></a>';

                        return $btn;
                    })
                    ->make(true);
        }
        return view('backend.admin.ballone.body.setting');
    }

    public function store(Request $request)
    {
        // return $request->all();

        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0',
        ]);

        FootballBodySetting::find(1)->update([
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'percentage' => $request->percentage
        ]);

        return response()->json(['success'=>'Body setting updated successfully.']);
    }

    public function show()
    {
        $data = FootballBodySetting::find(1);
        return response()->json($data);
    }
}
