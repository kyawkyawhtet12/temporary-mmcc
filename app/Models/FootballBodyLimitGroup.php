<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBodyLimitGroup extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'percentage', 'min_amount', 'max_amount' , 'status' ];
}
