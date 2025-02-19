<?php

namespace App\Models;

use App\Traits\FilterQuery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, FilterQuery;

    protected $fillable = [
        'amount',
        'user_id',
        'payment_provider_id',
        'phone',
        'transation_no',
        'transation_ss',
        'status',
        'agent_id',
        'by'
    ];

    public static function getDepositCount($date, $agent = null)
    {
        // return Payment::whereDate('created_at', $date)->where('status', 'Approved')->count();
        if ($agent) {
            return Payment::where('agent_id', $agent)->whereDate('created_at', $date)->where('status', 'Approved')->count();
        } else {
            return Payment::whereDate('created_at', $date)->where('status', 'Approved')->count();
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
        if ($this->provider) {
            return $this->provider?->name;
        }

        if (!$this->payment_provider_id && !$this->by) {
            return "Recharge By {$this->agent->name}.";
        }

        return "Recharge By {$this->admin->name}.";
    }

    public function getActionTimeAttribute()
    {
        if ($this->status == 'Approved' || $this->status == 'Rejected') {
            return $this->updated_at->format("d-m-Y h:i A");
        }

        return "-";
    }

    public function getProcessTimeAttribute()
    {
        if ($this->status == 'Approved' || $this->status == 'Rejected') {
            return $this->created_at->diffForHumans($this->updated_at);
        }

        return "-";
    }
    public function getTime($time)
    {
        // return $time;
        return Carbon::parse($time)->format('d-m-Y');
    }
}
