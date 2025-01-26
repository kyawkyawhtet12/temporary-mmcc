<?php

use Carbon\Carbon;
use App\Models\Agent;
use App\Models\ThreeDigitSetting;
use Illuminate\Support\Facades\Auth;

//

function dateFormat($date, $format = "d-m-Y")
{
    return Carbon::parse($date)->format($format);
}

function dateTimeFormat($date, $format = "d-m-Y h:i A")
{
    return Carbon::parse($date)->format($format);
}

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


function getTwoDigit($number)
{
    return sprintf('%02d', $number);
}

function getBadgeColor($setting,$amount)
{
    if( $check = $setting->where('max_amount', '<=',  $amount)->first() ){
        return "background-color : $check->color";
    }

    return "color: #333";
}

function getNetAmountStatus($amount)
{
    return match (true) {
        $amount > 0  => "Win",
        $amount < 0  => "No Win",
        $amount == 0 => "...",
    };
}

function getTotalAmountRecords($data)
{
    $total = [
        'betting' => number_format($data->sum('betting_amount')),
        'win'     => number_format($data->sum('win_amount')),
        'net'     => number_format($data->sum('net_amount')),
        'status'  => getNetAmountStatus($data->sum('net_amount'))
    ];

    return $total;
}


function getFootballBettingStatus($status)
{
    switch ($status) {
        case 0:
            return "Pending";
            break;
        case 1:
            return "Win";
            break;
        case 2:
            return "No Win";
            break;
        case 3:
            return "Draw";
            break;
        case 4:
            return "Refund";
            break;

        default:
            return "-";
            break;
    }
}

if (! function_exists('setActive')) {
    function setActive($routeName, $output = 'active')
    {
        return request()->routeIs($routeName) ? $output : '';
    }
}
