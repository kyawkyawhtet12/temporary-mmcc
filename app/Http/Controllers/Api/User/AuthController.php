<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\User;

class AuthController extends ResponseController
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }

            if (Auth::attempt(['user_id' => $request->userId, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('user_token')->plainTextToken;

                return $this->successResponse([
                    'user' => $user,
                    'token' => $token,
                ], 'User logged in successfully', 200);
            }

            return $this->errorResponse('Invalid credentials', 401);
        } catch (\Throwable $th) {
            return $this->errorResponse('Login failed', 500, ['error' => $th->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the user's token
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            return $this->successResponse([], 'User logged out successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Logout failed', 500, ['error' => $th->getMessage()]);
        }
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->successResponse($user, 'User Profile Fetched Successfully', 200);
    }
}
