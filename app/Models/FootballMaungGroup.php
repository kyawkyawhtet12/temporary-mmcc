<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMaungGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(FootballMaung::class, 'maung_group_id');
    }

    public function bet()
    {
        return $this->hasOne(FootballBet::class, 'maung_group_id');
    }
}
