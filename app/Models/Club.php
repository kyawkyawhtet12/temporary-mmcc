<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function getAllLeaguesAttribute()
    {
        return self::whereName($this->name)->pluck('league_id');
    }
}
