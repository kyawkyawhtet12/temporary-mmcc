<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoAdd extends Model
{
    use HasFactory;

    protected $fillable = [ 'date' , 'done' , 'status' ];
}
