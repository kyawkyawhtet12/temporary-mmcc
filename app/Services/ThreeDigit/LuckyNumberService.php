<?php

namespace App\Services\ThreeDigit;

use App\Models\ThreeWinner;
use App\Models\BettingRecord;
use App\Services\RecordService;
use App\Services\UserLogService;
use App\Models\ThreeDigitSetting;
use Illuminate\Support\Facades\DB;

class LuckyNumberService
{
    public function handle($data)
    {
        DB::transaction(function () use ($data) {

            BettingRecord::setData($data->draws);

            $this->addWin($data->win_draws, $data->lucky_number->id);

            $data->update([ 'status' => 0 ]);

            $data->lucky_number()->update([ 'status' => "Approved" ]);

            ThreeDigitSetting::addNextLuckyDate($data->date);

        });
    }

    protected function addWin($win_draws, $lucky_number_id)
    {
        foreach ($win_draws as $draw) {
            ThreeWinner::create([
                'three_lucky_number_id' => $lucky_number_id,
                'three_lucky_draw_id' => $draw->id,
                'status' => 'Full',
                'user_id' => $draw->user_id,
                'agent_id' => $draw->agent_id
            ]);

            $draw->betting_record()->update([
                'result' => 'Win',
                'win_amount' => $draw->win_amount
            ]);

            (new RecordService())->add($draw->user, $draw->win_amount, "3D");

            (new UserLogService())->add($draw->user, $draw->win_amount, '3D Win');

            $draw->user->increment('amount', $draw->win_amount);
        }

    }
}
