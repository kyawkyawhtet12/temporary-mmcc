<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $data = Admin::where('is_admin',0)->latest()->get();
        return view('backend.admin.staff.index', compact('data'));
    }

    public function store(Request $request)
    {
        // return $request->all();

        if (is_null($request->staff_id)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
            ]);
            $password = Hash::make($request->password);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|unique:admins,email,'.$request->staff_id,
                'password' => 'nullable|string|min:8',
            ]);

            if ($request->password) {
                $password = Hash::make($request->password);
            } else {
                $password = Admin::where('id', $request->staff_id)->value('password');
            }
        }
        Admin::updateOrCreate([
            'id'   => $request->staff_id,
        ], [
            'name'     => $request->name,
            'email' => $request->email,
            'password' => $password
        ]);

        return response()->json(['success'=>'Staff saved successfully.']);
    }

    public function edit($id)
    {
        $data = Admin::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        Admin::find($id)->delete();
        return response()->json(['success'=>'Staff deleted successfully.']);
    }
}
