<?php

namespace App\Http\Controllers\Backend;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerImageController extends Controller
{
    public function index()
    {
        $data = Banner::where('status', 1)->first();
        return view("backend.admin.banner.index", compact("data"));
    }

    public function store(Request $request)
    {
        // return $request->all();

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png'
        ]);
        
        $path = $request->file('image')->store('banner');

        $banner = Banner::firstOrNew([ 'id' =>  1 ]);
        $banner->image = $path;
        $banner->status = 1;
        $banner->save();

        return back()->with('success', '* Successfully Created');
    }

    public function destroy(Request $request, $id)
    {
        Banner::findOrFail($id)->update(['status' => 0 ]);
        return back()->with('success', '* Successfully Deleted');
    }
}
