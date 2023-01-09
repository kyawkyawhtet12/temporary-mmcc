<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Http\Controllers\Controller;
use App\Models\FootballMaungLimit;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class MaungLimitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FootballMaungLimit::all();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Edit" class="editLimit"><i class="fa fa-edit text-inverse m-r-10"></i></a>';

                        return $btn;
                    })
                    ->make(true);
        }
        return view('backend.admin.ballone.maung.limit');
    }

    public function store(Request $request)
    {
        // return $request->all();

        $request->validate([
            'min_teams' => 'required|numeric|min:0',
            'max_teams' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            
        ]);

        FootballMaungLimit::find(1)->update([
            'min_teams' => $request->min_teams,
            'max_teams' => $request->max_teams,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount
        ]);

        return response()->json(['success'=>'Maung limit updated successfully.']);
    }

    public function show()
    {
        $data = FootballMaungLimit::find(1);
        return response()->json($data);
    }
}
