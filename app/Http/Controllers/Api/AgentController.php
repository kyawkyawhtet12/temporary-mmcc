<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AgentResource;

class AgentController extends Controller
{
    use ApiResponser;

    public function dashboard()
    {
        $data = new AgentResource(Auth::user());
        $message = "Success";
        return $this->successResponse($data, $message);
    }

    public function profile()
    {
        $data = new AgentResource(Auth::user());
        $message = "Success";
        return $this->successResponse($data, $message);
    }

    public function profile_update(Request $request)
    {
        $agent = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            // 'phone' => 'required|numeric|unique:agents',
            'password' => 'nullable|confirmed',
        ]);

        $agent->name = $request->name;
        // $agent->phone = $request->phone;

        if ($request->password) {
            $agent->password = Hash::make($request->password);
        }

        $agent->save();

        $data = new AgentResource(Auth::user());
        $message = "Success";
        return $this->successResponse($data, $message);
    }
}
