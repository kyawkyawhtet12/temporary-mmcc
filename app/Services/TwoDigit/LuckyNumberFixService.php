<?php

namespace App\Services\TwoDigit;

use App\Models\UserLog;
use App\Models\TwoWinner;
use App\Models\WinRecord;
use App\Models\TwoLuckyDraw;
use App\Models\BettingRecord;
use App\Services\RecordService;
use App\Services\UserLogService;
use Illuminate\Support\Facades\DB;

class LuckyNumberFixService
{
    public function handle($data)
    {
        DB::transaction(function () use ($data) {

            $draws = TwoLuckyDraw::query()
                                ->where('lottery_time_id', $data->lottery_time_id)
                                ->whereDate('created_at', $data->date);

            BettingRecord::setData($draws);

            $win_draws = $draws->where('two_digit_id', $data->two_digit_id)->get();

            $this->removeWin($win_draws, $data->id);

        });
    }

    protected function removeWin($win_draws, $number_id)
    {

        TwoWinner::where("two_lucky_number_id", $number_id)->delete();

        foreach ($win_draws as $draw) {

            WinRecord::where('user_id', $draw->user_id)
            ->where('type', '2D')
            ->whereDate('created_at', $draw->created_at)
            ->delete();

            UserLog::where('user_id', $draw->user_id)
            ->where('operation', '2D Win')
            ->whereDate('created_at', $draw->created_at)
            ->delete();

            $draw->user->decrement('amount', $draw->win_amount);
        }
    }
}
