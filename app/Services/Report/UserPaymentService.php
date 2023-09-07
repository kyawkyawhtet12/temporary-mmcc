<?php

namespace App\Services\Report;

use App\Models\User;

class UserPaymentService
{
    public function handle()
    {
        User::with('agent','payment_reports')
            ->chunkById(500, function ($users) {
                    foreach ($users as $user) {
                        $user->payment_reports()->create([
                            'agent_id' => $user->agent?->id
                        ]);
                    }
                });
    }
}
