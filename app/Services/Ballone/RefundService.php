<?php

namespace App\Services\Ballone;

use App\Models\FootballBody;
use App\Models\FootballMaung;
use Illuminate\Support\Facades\DB;
use App\Models\FootballRefundHistory;
use Illuminate\Support\Facades\Log;

class RefundService
{

    public function bodyRefund($match)
    {
        DB::beginTransaction();
        try {
            $bodies = FootballBody::with("user", "bet")->where('match_id', $match->id)->get();

            foreach ($bodies as $body) {
                $this->processRefund($body, 'FootballBody');
            }

            DB::commit();

            Log::info("Refund successful for FootballBody entries in match ID: " . $match->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund failed for FootballBody in match ID: ' . $match->id . ' - ' . $e->getMessage());
            throw new \Exception('Refund failed for FootballBody in match ID: ' . $match->id);
        }
    }

    public function maungRefund($match)
    {
        DB::beginTransaction();
        try {
            $maungs = FootballMaung::with('user', 'bet')->where('match_id', $match->id)->get();

            foreach ($maungs as $maung) {
                $this->maungMatchRefund($maung);
            }

            DB::commit();

            Log::info("Refund successful for FootballMaung entries in match ID: " . $match->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Refund failed for FootballMaung in match ID: ' . $match->id . ' - ' . $e->getMessage());
            throw new \Exception('Refund failed for FootballMaung in match ID: ' . $match->id);
        }
    }

    private function maungMatchRefund($maung)
    {
        if (!$maung->bet) {
            Log::error('Bet is missing for Maung ID: ' . $maung->id);
            throw new \Exception('Bet is missing for Maung ID: ' . $maung->id);
        }

        $maung->update(['status' => 4, 'refund' => 1]);

        if ($maung->bet->count > 0) {
            $maung->bet->decrement('count', 1);
        }

        $betting = $maung->bet->bet;

        if ($betting) {
            (new MaungService())->calculation($betting, $maung);

            if ($maung->bet->count == 1) {
                $this->history_add($maung, $betting);
            }

            Log::info("Refund processed for Maung ID: " . $maung->id);
        } else {
            Log::error('Betting information is missing for Maung ID: ' . $maung->id);
            throw new \Exception('Betting information is missing for Maung ID: ' . $maung->id);
        }
    }

    private function history_add($data, $betting)
    {
        try {
            // Increment the user's amount and log refund details atomically
            $data->user->increment('amount', (int)$betting->amount);
            $betting->update(['status' => 4]);

            FootballRefundHistory::create([
                'agent_id' => $data->agent_id,
                'user_id'  => $data->user_id,
                'bet_id'   => $betting->id
            ]);

            Log::info("Refund history added for user ID: " . $data->user_id . " and bet ID: " . $betting->id);
        } catch (\Exception $e) {
            Log::error('Failed to add refund history for user ID: ' . $data->user_id . ' - ' . $e->getMessage());
            throw new \Exception('Failed to add refund history for user ID: ' . $data->user_id);
        }
    }

    private function processRefund($body, $type)
    {
        $body->update(['refund' => 1]);
        $this->history_add($body, $body->bet);

        Log::info("Refund processed for {$type} ID: " . $body->id);
    }
}
