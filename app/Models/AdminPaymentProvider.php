<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminPaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner',
        'phone_number',
        'image',
        'status'
    ];

    protected $casts = [
        'image' => Image::class,
    ];
}
