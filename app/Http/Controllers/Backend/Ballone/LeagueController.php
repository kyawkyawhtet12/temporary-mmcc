<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Monarobase\CountryList\CountryListFacade;

class LeagueController extends Controller
{
    public function index(Request $request)
    {
        // $allCountries = CountryListFacade::getList('en');
        // return $allCountries;

        if ($request->ajax()) {
            $query = League::orderBy('id', 'DESC')->get();
        
            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($league) {
                        return date("F j, Y, g:i A", strtotime($league->created_at));
                    })
                    ->addColumn('action', function ($league) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$league->id.'" data-original-title="Edit" class="edit btn btn-outline-warning editLeague">Edit</a>';
                        
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$league->id.'" data-original-title="Delete" class="btn btn-outline-danger deleteLeague">Delete</a>';
                        return $btn;
                    })
                    
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('backend.admin.ballone.league.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            // 'country' => 'required',
            'level' => 'nullable',
        ]);
    
        League::updateOrCreate([
            'id'   => $request->league_id,
        ], [
            'name'     => $request->name,
            'country' => '-',
            'level' => $request->level,
        ]);
   
        return response()->json(['success'=>'League saved successfully.']);
    }

    public function edit($id)
    {
        $league = League::find($id);
        return response()->json($league);
    }

    public function destroy($id)
    {
        League::find($id)->delete();
        return response()->json(['success'=>'League deleted successfully.']);
    }
}
