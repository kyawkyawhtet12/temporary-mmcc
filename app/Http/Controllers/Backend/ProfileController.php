<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent,admin');
    }

    public function index()
    {
        $user = Auth::user();
        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        // return Auth::user();

        $request->validate([
            'password' => 'required',
            'confirm-password' => 'required|same:password',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', '* Successfully Updated');
    }
}
