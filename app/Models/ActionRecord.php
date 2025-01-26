<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($model) {
    //         self::logAction($model, 'create');
    //     });

    //     static::updated(function ($model) {
    //         self::logAction($model, 'update');
    //     });

    //     static::deleted(function ($model) {
    //         self::logAction($model, 'delete');
    //     });
    // }

    // protected static function logAction($model, $action)
    // {
    //     self::create([
    //         'user_id'    => Auth::id(),
    //         'action'     => $action,
    //         'table_name' => $model->getTable(),
    //         'record_id'  => $model->id,
    //         'data'       => $action !== 'delete' ? json_encode($model->getChanges()) : null,
    //         'ip_address' => request()->ip(),
    //         'user_agent' => request()->header('User-Agent'),
    //     ]);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
