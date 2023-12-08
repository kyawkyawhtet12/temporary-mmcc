<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatchStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id' , 'body_close' , 'maung_close' , 'all_close',
        'body_refund' , 'maung_refund' , 'all_refund' ,
        'admin_id'
    ];


}
