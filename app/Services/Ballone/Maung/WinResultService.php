<?php

namespace App\Services\Ballone\Maung;

use App\Models\FootballBet;

class WinResultService
{
    public function __construct(protected $filter) {

    }

    public function getResultRaw($type)
    {
        return match ($type) {
            'win'    => 'net_amount > amount',
            'no_win' => 'net_amount < amount',
            default => ''
        };
    }

    public function execute($round)
    {
       return FootballBet::query()
                            ->where('round', $round)
                            ->maungWinFilter()
                            ->with('user:id,user_id')
                            ->with('agent:id,name')
                            ->with('maung')
                            ->latest()

                            ->when($agent_ids = $this->filter['agent_id'] ?? NULL, function ($q) use ($agent_ids) {
                                return $q->whereIn('agent_id', $agent_ids);
                            })

                            ->when($user_id = $this->filter['user_id'] ?? NULL, function ($q) use ($user_id) {
                                return $q->whereHas('user', function ($r) use ($user_id) {
                                    $r->where('user_id', $user_id);
                                });
                            })

                            ->when($type = $this->filter['type'] ?? NULL, function ($q) use ($type) {
                                return $q->whereRaw($this->getResultRaw($type));
                            })

                            ->when($done = $this->filter['done'] ?? NULL, function ($q) use ($done) {
                                return $q->where('is_done', $done == 'success' ? 1 : 0);
                            });

    }

}
