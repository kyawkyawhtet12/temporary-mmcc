<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory,SoftDeletes;

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
