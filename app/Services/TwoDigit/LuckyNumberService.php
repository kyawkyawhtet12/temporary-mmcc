<?php

namespace App\Services\TwoDigit;

use App\Models\TwoWinner;
use App\Models\TwoLuckyDraw;
use App\Models\BettingRecord;
use App\Services\RecordService;
use App\Services\UserLogService;
use Illuminate\Support\Facades\DB;

class LuckyNumberService
{
    public function handle($data)
    {
        DB::transaction(function () use ($data) {

            $draws = TwoLuckyDraw::query()
                                ->where('lottery_time_id', $data->lottery_time_id)
                                ->whereDate('created_at', $data->date);

            BettingRecord::setData($draws);

            $win_draws = $draws->where('two_digit_id', $data->two_digit_id)->get();

            $this->addWin($win_draws, $data->two_digit_id);

        });
    }

    protected function addWin($win_draws, $number_id)
    {
        foreach ($win_draws as $draw) {

            TwoWinner::create([
                'two_lucky_number_id' => $number_id,
                'two_lucky_draw_id' => $draw->id,
                'user_id' => $draw->user_id,
                'agent_id' => $draw->agent_id
            ]);

            $draw->betting_record()->update([
                'result' => 'Win',
                'win_amount' => $draw->win_amount
            ]);

            (new RecordService())->add($draw->user, $draw->win_amount, "2D");

            (new UserLogService())->add($draw->user, $draw->win_amount, '2D Win');

            $draw->user->increment('amount', $draw->win_amount);
        }
    }
}
