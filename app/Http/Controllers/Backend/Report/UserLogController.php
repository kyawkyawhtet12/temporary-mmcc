<?php

namespace App\Http\Controllers\Backend\Report;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;

class UserLogController extends Controller
{

    public function index($id)
    {
        $user = User::findOrFail($id);

        $data = UserLog::with('user','agent')
                        ->where('user_id', $id)
                        ->orderBy('id', 'desc')
                        ->paginate(15);

        $user_id = $user->id;
        $select_type = 'all';

        return view("backend.admin.users.amount_details", compact("data", "user_id", "select_type"));
    }

    public function filter(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = UserLog::with('user','agent')
                        ->where('user_id', $id)
                        ->orderBy('id', 'desc');

        if($request->type && $request->type != 'all'){
            $data = $data->where('operation', $request->type);
        }

        if (!empty($request->start_date)){
            $data = $data->whereDate('created_at', '>=', $request->start_date);
        }

        if (!empty($request->end_date)){
            $data = $data->whereDate('created_at', '<=', $request->end_date);
        }

        $data = $data->paginate(15);

        $user_id = $user->id;
        $select_type = $request->type;

        return view("backend.admin.users.amount_details", compact("data","user_id", "select_type"));
    }

}
