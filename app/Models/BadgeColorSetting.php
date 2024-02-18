<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeColorSetting extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'min_amount' , 'max_amount' , 'color' , 'is_active' ];


}
