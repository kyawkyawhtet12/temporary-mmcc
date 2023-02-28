<?php

namespace App\Http\Controllers\Backend\Ballone;

use Carbon\Carbon;
use App\Models\FootballBet;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Http\Controllers\Controller;

class ReportDetailController extends Controller
{
    public function index($id)
    {
        $data = FootballMatch::findOrFail($id);

        return $data;
    }
}
