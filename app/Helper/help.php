<?php

use Carbon\Carbon;
use App\Models\ThreeDigitSetting;
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

function get_all_types()
{
    return [ '2D' ,'3D','Body','Maung' ];
}

function is_admin()
{
    return ( Auth::user()->is_admin ) ? true : false;
}

function get3DCurrentRound()
{
    $setting = ThreeDigitSetting::whereStatus(1)->first();
    return $setting ? $setting->id : 1;
}
