<?php

namespace App\Services\Ballone;
class ResultService
{
    protected
            $format,
            $limit = 0,
            $percent = 100,
            $goal_diff,
            $total_goals ,
            $first ,
            $second
        ;

    protected function setFees($fees)
    {
        $this->format = match (true) {
            (str_contains($fees, '+')) => '+',
            (str_contains($fees, '-')) => '-',
            default => '=',
        };

        list($limit, $this->percent) = explode($this->format, $fees) + [];
        $this->limit = (is_numeric($limit)) ? $limit : 0;
        $this->percent = $this->percent ?: 100;
    }

    public function calculate($all_fees, $scores)
    {
        foreach ($all_fees as $fees) {
            // Body Fees Result Calculation
            if($fees->body){
                $this->setFees($fees->body);
                $this->goal_diff = $fees->up_team == 1 ?
                                        ($scores['home'] - $scores['away']) :
                                        ($scores['away'] - $scores['home']);
                $this->bodyCalculation($fees);
            }

            // Goal Fees Result Calculation
            if($fees->goals){
                $this->setFees($fees->goals);
                $this->total_goals = $scores['home'] + $scores['away'];
                $this->goalsCalculation($fees);
            }
        }
    }

    protected function bodyCalculation($fees)
    {
        if ($this->format === "=") {
            $this->percent = ($this->goal_diff == $this->limit) ? 0 : 100;
            $this->calculateBodyPercent("", "");
        }

        if ($this->format === "+") {
            $this->percent = ($this->goal_diff == $this->limit) ? $this->percent : 100;
            $this->calculateBodyPercent("", "-");
        }

        if ($this->format === "-") {
            $this->percent = ($this->goal_diff == $this->limit) ? $this->percent : 100;
            $this->calculateBodyPercent("-", "");
        }

        $fees->result->update([
            'home' => ($fees->up_team == 1) ? $this->first : $this->second,
            'away' => ($fees->up_team == 2) ? $this->first : $this->second
        ]);
    }

    protected function calculateBodyPercent($default_1, $default_2)
    {
        // $percent = ($this->percent ?: 100);

        $this->first = match (true) {
            ($this->goal_diff < $this->limit) => -$this->percent,
            ($this->goal_diff > $this->limit) => $this->percent,
            default => $default_1 . $this->percent,
        };

        $this->second = match (true) {
            ($this->goal_diff < $this->limit) => $this->percent,
            ($this->goal_diff > $this->limit) => -$this->percent,
            default => $default_2 . $this->percent,
        };
    }

    protected function goalsCalculation($fees)
    {
        if ($this->total_goals > $this->limit) {
            $over = 100;
            $under = -100;
        }

        if ($this->total_goals < $this->limit) {
            $over = -100;
            $under = 100;
        }

        if ($this->total_goals == $this->limit) {
            $percent = ($this->format === "=") ? 0 : $this->percent;

            // dd($this->percent);
            $over    = ($this->format === "-") ? -$percent : $percent;
            $under   = ($this->format === "+") ? -$percent : $percent;
        }

        $fees->result->update([
            'over'  => $over,
            'under' => $under,
        ]);
    }
}
