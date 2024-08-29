<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballBet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function body()
    {
        return $this->belongsTo(FootballBody::class, 'body_id');
    }

    public function maung()
    {
        return $this->belongsTo(FootballMaungGroup::class, 'maung_group_id');
    }

    public function maung_teams()
    {
        return $this->hasMany(FootballMaung::class, 'maung_group_id', 'maung_group_id');
    }

    public function maung_win()
    {
        return $this->hasOne(WinRecord::class, 'betting_id', 'maung_group_id')
        ->where('type', 'Maung')
        ->where('status', '!=', 2);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function betting_record()
    {
        return $this->belongsTo(BettingRecord::class, 'betting_record_id');
    }

    public function getAmountFormatAttribute()
    {
        return number_format($this->amount);
    }

    public function getStatusFormatAttribute()
    {
        switch ($this->status) {
            case 0:
                return "Pending";
                break;
            case 1:
                return "Win";
                break;
            case 2:
                return "No Win";
                break;
            case 3:
                return "Draw";
                break;
            case 4:
                return "Refund";
                break;

            default:
                return "-";
                break;
        }
    }

    public function scopeMaungWinFilter($query)
    {
        $query->whereNotNull('maung_group_id')->whereStatus(1);
    }

    public function scopeDoneFilter($query , $done = 0)
    {
        $query->where('is_done', $done);
    }

    public function getResultAttribute()
    {
        return $this->net_amount > $this->amount ? 'Win' : 'No Win';
    }

    public function getResultColorAttribute()
    {
        return $this->net_amount > $this->amount ? 'text-info' : 'text-danger';
    }

    public function getBettingTimeAttribute()
    {
        return $this->created_at->format('d-m-Y h:i A');
    }
}
