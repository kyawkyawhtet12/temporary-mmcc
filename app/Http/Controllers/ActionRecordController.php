<?php

namespace App\Http\Controllers;

use App\Models\ActionRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActionRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionRecord::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        $actionRecords = $query->with(['admin', 'footballMatch'])->latest()->paginate(10);

        // $json = json_decode($actionRecords[0]->data);

        // return $actionRecords;

        return view('backend.admin.action_records.index', compact('actionRecords'));
    }
}
