<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [ 'image', 'status' ];

    protected $casts = [
        'image' => Image::class,
    ];
}
