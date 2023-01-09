<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LotteryTime extends Model
{
    use HasFactory;

    protected $fillable = [ 'time' , 'type' , 'status'];

    // Type 0 - Thai Time , 1 - Dubai Time

    public function getTypeFormatAttribute()
    {
        return ($this->type) ? 'Dubai' : 'Thai';
    }

    public function getTimeNumberAttribute()
    {
        // return trim($this->time, " AM");
        return Carbon::parse($this->time)->format('H:i');
    }
}
