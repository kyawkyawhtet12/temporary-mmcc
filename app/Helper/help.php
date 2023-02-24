<?php

use Carbon\Carbon;

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
    // $date = Carbon::parse($match->date)->format('d-m-Y');
    // $time = Carbon::parse($match->time)->format('g:i A');
    // return $date . '  ' . $time ;

    return Carbon::parse($match->date_time)->format('d-m-Y g:i A');
}

function getHomeScore($score)
{
    if ($score) {
        $d = explode("-", $score);
        return str_replace(' ', '', $d[0]);
    } else {
        return null;
    }
}

function getAwayScore($score)
{
    if ($score) {
        $d = explode("-", $score);
        return str_replace(' ', '', $d[1]);
    } else {
        return null;
    }
}
