<?php

namespace App\Models;

use App\Traits\FilterQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    use HasFactory, FilterQuery;

    protected $fillable = [
        'amount',
        'user_id',
        'payment_provider_id',
        'phone',
        'payment_name',
        'remark',
        'status',
        'agent_id',
        'by'
    ];

    public static function getWithdrawalCount($date, $agent = null)
    {

        if($agent) {
            return Cashout::where('agent_id', $agent)->whereDate('created_at', $date)->where('status', 'Approved')->count();
        } else {
            return Cashout::whereDate('created_at', $date)->where('status', 'Approved')->count();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'by');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function scopeFilterDates($query)
    {
        $date = explode(" - ", request()->input('date_range', ""));
        if (count($date) != 2) {
            $date = [now()->today()->format("Y-m-d"), now()->format("Y-m-d")];
        }
        return $query->whereBetween('created_at', [$date['0'] . " 00:00:00", $date['1'] . " 23:59:59"]);
    }


    public function getProviderNameAttribute()
    {
        if( $this->payment_name){
            return $this->payment_name;
        }

        if ($this->provider) {
            return $this->provider?->name;
        }

        if(!$this->payment_provider_id && !$this->by){
            return "Cashout By {$this->agent->name}.";
        }

        return "Cashout By {$this->admin->name}.";

    }

    public function getActionTimeAttribute()
    {
        if( $this->status == 'Approved' || $this->status == 'Rejected' ){
            return $this->updated_at;
        }

        return "-";
    }

    public function getProcessTimeAttribute()
    {
        if( $this->status == 'Approved' || $this->status == 'Rejected' ){
            return $this->created_at->diffForHumans($this->updated_at);
        }

        return "-";
    }

}
