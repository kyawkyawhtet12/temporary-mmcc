<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BadgeColorSetting;
use App\Models\FootballBodyLimitGroup;

class ReportAmountColorController extends Controller
{
    public function index(Request $request, $type)
    {
        if(!in_array($type, ['2D', '3D'])) return redirect("/admin/dashboard");

        $groups = BadgeColorSetting::where('name', $type)->orderBy('max_amount')->get();

        return view('backend.admin.setting.report-color', compact('groups', 'type'));
    }

    public function store(Request $request, $type)
    {

        if(!in_array($type, ['2D', '3D'])) return redirect("/admin/dashboard");

        $request->validate([
            'color' => 'required|string|max:100',
            'max_amount' => 'required|numeric|min:0',
        ]);

        BadgeColorSetting::updateOrCreate(
        [ 'id' => $request->id ],
        [
            'name'       => $type,
            'color'      => $request->color,
            'max_amount' => $request->max_amount
        ]);

        return back()->with('success', '* Successfully Done');
    }

    public function destroy($id)
    {
        BadgeColorSetting::findOrFail($id)->delete();

        return response()->json("success");
    }
}
