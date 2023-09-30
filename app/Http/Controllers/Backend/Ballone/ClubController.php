<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\Club;
use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();

        if ($request->ajax()) {

            $query = DB::table('clubs')
                        ->join('leagues', 'clubs.league_id', '=', 'leagues.id')
                        ->select('clubs.id', 'clubs.name', 'clubs.created_at' , 'leagues.name as leagues')
                        ->where('clubs.deleted_at','=',NULL)
                        ->latest()
                        ->get()
                        ->groupBy('name')
                        ->map(function($d){
                            return collect($d->first())->put('leagues', $d->pluck('leagues')->implode( ' , '));
                        });

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function($club) {
                    return $club['name'];
                })
                ->addColumn('league', function ($club) use ($query) {
                    return $club['leagues'];
                })
                ->addColumn('created_at', function ($club) {
                    return date("F j, Y, g:i A", strtotime($club['created_at']));
                })
                ->addColumn('action', function ($club) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $club['id'] . '" data-original-title="Edit" class="edit btn btn-outline-info editClub">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $club['id'] . '" data-original-title="Delete" class="btn btn-outline-danger deleteClub">Delete</a>';
                    return $btn;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->rawColumns(['league', 'action'])
                ->make(true);
        }

        return view('backend.admin.ballone.club.index', compact('leagues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'league_id' => 'required',
        ]);

        $check = Club::whereName($request->name)->pluck('id')->toArray();

        if( count($check) && !in_array($request->club_id , $check )){
            return response()->json(['error' => 'Club name is already added']);
        }

        if($request->club_id){

            $club = Club::find($request->club_id);

            if ( $club->name != $request->name ){
                Club::whereName($club->name)->update(['name' => $request->name ]);
            }

            Club::whereName($request->name)->whereNotIn("league_id", $request->league_id)->delete();

        }

        foreach ($request->league_id as $league) {
            Club::firstOrCreate([
                'name' => $request->name,
                'league_id' => $league,
            ]);
        }

        return response()->json(['success' => 'Club saved successfully.']);
    }

    public function edit($id)
    {
        $club = Club::select('name')->findOrFail($id);

        return response()->json(
            [
                'club' => $club->name,
                'league' => Club::whereName($club->name)->pluck('league_id')
            ]
        );
    }

    public function destroy($id)
    {
        $club = Club::select('name')->findOrFail($id);
        Club::whereName($club->name)->delete();
        return response()->json(['success' => 'Club deleted successfully.']);
    }
}
