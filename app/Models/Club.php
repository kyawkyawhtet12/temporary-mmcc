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
        // return $this->name;

        $clubs =  Club::select('league_id')->with('league')->where('name', $this->name)->get();
        $league = "";

        foreach ($clubs as $club) {
            $league .= $club->league->name . " , ";
        }

        return rtrim($league, ' , ');
    }
}
