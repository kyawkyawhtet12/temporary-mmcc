<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerImageController extends Controller
{
    public function index()
    {
        $agents = Agent::withCount('banners')->paginate(15);
        return view("backend.admin.banner.index", compact("agents"));
    }

    public function edit($id)
    {
        $agent = Agent::findOrFail($id);
        return view("backend.admin.banner.edit", compact("agent"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpg,jpeg,png'
        ]);

        $agent = Agent::findOrFail($id);

        foreach( $request->images as $image ){

            $path = $image->store('banner');

            $banner = new Banner();
            $banner->image = request()->getSchemeAndHttpHost() . '/image/'. $path;
            $banner->agent_id = $agent->id;
            $banner->save();
        }

        return back()->with('success', '* Successfully Added');
    }


    public function destroy(Request $request, Banner $banner)
    {
        // $oldImage = $banner->getRawOriginal('image') ?? '';

        $test = explode("/", $banner->image);

        $oldImage = "{$test[4]}/{$test[5]}";

        Storage::delete($oldImage);
        $banner->delete();

        return back()->with('success', '* Successfully Deleted');
    }
}
