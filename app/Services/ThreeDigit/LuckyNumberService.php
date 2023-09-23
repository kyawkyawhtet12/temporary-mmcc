<?php

namespace App\Services\ThreeDigit;

use Carbon\Carbon;
use App\Models\TwoWinner;
use App\Models\ThreeWinner;
use App\Models\TwoLuckyDraw;
use App\Models\BettingRecord;
use App\Models\ThreeLuckyDraw;
use App\Services\RecordService;
use App\Models\ThreeLuckyNumber;
use App\Services\UserLogService;
use Illuminate\Support\Facades\DB;

class LuckyNumberService
{
    public function handle($data)
    {
        DB::transaction(function () use ($data) {

            $draws = ThreeLuckyDraw::query()->where('round', $data->round);

            BettingRecord::setData($draws);

            $win_draws = $draws->where('three_digit_id', $data->three_digit_id)->get();

            $this->addWin($win_draws, $data->three_digit_id);

            (new ThreeLuckyNumber())->add_new_round();

        });
    }

    protected function addWin($win_draws, $number_id)
    {
        foreach ($win_draws as $draw) {

            ThreeWinner::create([
                'three_lucky_number_id' => $number_id,
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
