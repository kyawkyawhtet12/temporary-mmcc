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

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
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
}
