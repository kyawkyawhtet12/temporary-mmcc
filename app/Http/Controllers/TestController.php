<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testing()
    {
        return response()->json([
            'success' => true,
            'user' => auth()->user()
        ]);
    }
}
