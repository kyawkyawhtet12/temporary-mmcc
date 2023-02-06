<?php

namespace App\Http\Controllers\Backend\Ballone;

use App\Models\Club;
use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        if ($request->ajax()) {
            $query = Club::latest()->get();

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('league', function ($club) {
                        return $club->league->name;
                    })
                    ->addColumn('created_at', function ($club) {
                        return date("F j, Y, g:i A", strtotime($club->created_at));
                    })
                    ->addColumn('action', function ($club) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$club->id.'" data-original-title="Edit" class="edit btn btn-outline-info editClub">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$club->id.'" data-original-title="Delete" class="btn btn-outline-danger deleteClub">Delete</a>';
                        return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                if (Str::contains(Str::lower($row['code']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                return false;
                            });
                        }
                    })
                    ->rawColumns(['league','action'])
                    ->make(true);
        }
        return view('backend.admin.ballone.club.index', compact('leagues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'league_id' => 'required',
        ]);

        if ($request->club_id) {
            $club = Club::find($request->club_id);
            Club::where('name', $club->name)->delete();
        }

        foreach ($request->league_id as $league) {
            Club::create([
                'name' => $request->name,
                'code' => $request->code,
                'league_id' => $league,
            ]);
        }
        
        return response()->json(['success'=>'Club saved successfully.']);
    }

    public function edit($id)
    {
        $club = Club::find($id);
        $league = Club::where('name', $club->name)->pluck('league_id');

        $data = [
            'club' => $club,
            'league' => $league
        ];
        return response()->json($data);
    }

    public function destroy($id)
    {
        Club::find($id)->delete();
        return response()->json(['success'=>'Club deleted successfully.']);
    }
}
