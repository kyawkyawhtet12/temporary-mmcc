<?php

use Carbon\Carbon;

//
function get_date_time_format($date)
{
    return Carbon::parse($date)->format('d/m/Y g:i A');
}
