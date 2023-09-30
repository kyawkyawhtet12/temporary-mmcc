<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class League extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function clubs(){
        return $this->hasMany(Club::class, 'league_id');
    }
}
