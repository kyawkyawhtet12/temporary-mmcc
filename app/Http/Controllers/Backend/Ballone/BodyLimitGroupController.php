<?php

namespace App\Http\Controllers\Backend\Ballone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FootballBodyLimitGroup;

class BodyLimitGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = FootballBodyLimitGroup::select('id','name','max_amount')->orderBy("max_amount")->get();
        return view('backend.admin.ballone.body.limit-group', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group' => 'required|string|max:100',
            'limit_amount' => 'required|numeric|min:0',
        ]);

        FootballBodyLimitGroup::updateOrCreate(
        [ 'id' => $request->id ],
        [
            'name' => $request->group,
            'max_amount' => $request->limit_amount
        ]);

        return back()->with('success', '* Successfully Done');
    }

    public function destroy($id)
    {
        FootballBodyLimitGroup::findOrFail($id)->delete();

        return response()->json("success");
    }
}
