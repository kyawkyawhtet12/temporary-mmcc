<?php

use Carbon\Carbon;

//
function get_date_time_format($date)
{
    return Carbon::parse($date)->format('d/m/Y g:i A');
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
