<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Models\FootballMaungZa;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class MaungZaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FootballMaungZa::orderBy('teams', 'asc')->get();
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Edit" class="editBtn"><i class="fa fa-edit text-inverse mr-2"></i></a>';

                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="deleteBtn"><i class="fa fa-trash text-danger"></i></a>';

                        return $btn;
                    })
                    ->make(true);
        }
        return view('backend.admin.ballone.maung.za');
    }

    public function store(Request $request)
    {
        $request->validate([
            'teams' => 'required|numeric|min:0',
            'percent' => 'required|numeric|min:0'
        ]);

        FootballMaungZa::updateOrCreate(
            [ 'teams' => $request->teams ],
            [ 'za' => 2 , 'percent' => $request->percent ]
        );

        return response()->json(['success'=>'Maung Za updated successfully.']);
    }

    public function show($id)
    {
        $data = FootballMaungZa::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        FootballMaungZa::findOrFail($id)->delete();
        return response()->json(['success'=>'Maung Za deleted successfully.']);
    }
}
