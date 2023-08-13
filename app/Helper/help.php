<?php

use App\Models\Enabled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

//

function get_date_format($match)
{
    return Carbon::parse($match->date_time)->format('Y-m-d');
}

function get_time_format($match)
{
    return Carbon::parse($match->date_time)->format('H:i');
}

function get_date_time_format($match)
{
    return Carbon::parse($match->date_time)->format('d-m-Y g:i A');
}

function check_plus_format($number)
{
    return ($number > 0) ? "+{$number}" : $number;
}

function check_close_all_bets()
{
    $data = Enabled::find(1)->close_all_bets;
    return ($data) ? '' : 'checked';
}

function get_all_types()
{
    $data = ['2D', '3D','Body','Maung'];

    return $data;
}

function is_admin()
{
    return ( Auth::user()->is_admin ) ? true : false;
}
