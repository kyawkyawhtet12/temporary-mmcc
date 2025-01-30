<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
            case 1:
                return "Win";
            case 2:
                return "No Win";
            case 3:
                return "Draw";
            case 4:
                return "Refund";
            default:
                return "-";
        }
    }

    public function scopeMaungWinFilter($query)
    {
        $query->whereNotNull('maung_group_id')->whereStatus(1);
    }

    public function scopeDoneFilter($query, $done = 0)
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

    public function scopeFilterByDate($query, $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date) : today()->subDay(14);
        $end_date   = $request->end_date   ? Carbon::parse($request->end_date)   : today();

        return $query->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('status', $value);
    }
}
