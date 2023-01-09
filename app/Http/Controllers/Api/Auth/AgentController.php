<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AgentResource;

class AgentController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        return $request->all();
    }
    
    public function login(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required'
        ]);

        if (Auth::guard('agent')->attempt($data)) {
            $user = Auth::guard('agent')->user();

            // if ($user->status == 0) {
            //     $message = "Your account is inactive.";
            //     return $this->errorResponse($message, 422);
            // }
            
            $data = [
                'user' => new AgentResource($user),
                'token' => $user->createToken('authToken')->accessToken
            ];

            $message = "Login Successfully";
            return $this->successResponse($data, $message);
        } else {
            $message = "Invalid Credentials.";
            return $this->errorResponse($message, 422);
        }
    }

    public function logout()
    {
        // return response()->json(Auth::user()->token());
        return Auth::user();
        $user = Auth::user()->token()->revoke();
        
        $message = "Successfully logged out.";
        return $this->successResponse(null, $message);
    }
}
