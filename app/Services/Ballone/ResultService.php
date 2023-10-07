<?php

namespace App\Services\Ballone;

class ResultService
{
    protected
        $format, $validation,
        $limit = 0,
        $percent = 100,
        $goal_diff, $home_goal_diff, $away_goal_diff,
        $total_goals,
        $first,
        $second,
        $home, $away,
        $over, $under,
        $body_error, $goal_error;

    public function calculate($all_fees, $scores)
    {
        foreach ($all_fees as $fees) {
            // Body Fees Result Calculation

            if ($fees->body) {

                $this->body_handle($fees, $scores);

                $fees->result->update([
                    'home' => ($fees->up_team == 1) ? $this->home : $this->away,
                    'away' => ($fees->up_team == 2) ? $this->home : $this->away,
                    'body_error' => $this->body_error
                ]);
            }

            // Goal Fees Result Calculation
            if ($fees->goals) {

                $this->goal_handle($fees, $scores);

                $fees->result->update([
                    'over'  => $this->over,
                    'under' => $this->under,
                    'goal_error' => $this->goal_error
                ]);
            }
        }
    }

    protected function checkFees($fees)
    {
        $this->format = match (true) {
            (str_contains($fees, '+')) => '+',
            (str_contains($fees, '-')) => '-',
            (str_contains($fees, '=')) => '=',
            default => 'error',
        };

        $this->validation = (new FeesValidation())->checkFees($fees);
    }

    protected function body_handle($fees, $scores)
    {
        $this->checkFees($fees->body);

        if (!$this->validation) {
            $this->home = 0;
            $this->away = 0;
            $this->body_error = 1;
        }else{
            $this->goal_diff = $fees->up_team == 1 ?
                    ($scores['home'] - $scores['away']) : ($scores['away'] - $scores['home']);
            $this->body_error = 0;
            $this->setFees($fees->body);
            $this->bodyCalculation($fees);
        }
    }

    protected function goal_handle($fees, $scores)
    {
        $this->checkFees($fees->goals);

        if (!$this->validation) {
            $this->over = 0;
            $this->under = 0;
            $this->goal_error = 1;
        }else{
            $this->goal_error = 0;
            $this->total_goals = $scores['home'] + $scores['away'];
            $this->setFees($fees->goals);
            $this->goalsCalculation($fees);
        }
    }

    protected function setFees($fees)
    {
        list($limit, $this->percent) = explode($this->format, $fees) + [];
        $this->limit = (is_numeric($limit)) ? $limit : 0;
        $this->percent = $this->percent ?: 100;
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
    }

    protected function calculateBodyPercent($default_1, $default_2)
    {
        $this->home = match (true) {
            ($this->goal_diff < $this->limit) => -$this->percent,
            ($this->goal_diff > $this->limit) => $this->percent,
            default => $default_1 . $this->percent,
        };

        $this->away = match (true) {
            ($this->goal_diff < $this->limit) => $this->percent,
            ($this->goal_diff > $this->limit) => -$this->percent,
            default => $default_2 . $this->percent,
        };
    }

    protected function goalsCalculation($fees)
    {
        if ($this->total_goals > $this->limit) {
            $this->over = 100;
            $this->under = -100;
        }

        if ($this->total_goals < $this->limit) {
            $this->over = -100;
            $this->under = 100;
        }

        if ($this->total_goals == $this->limit) {
            $percent = ($this->format === "=") ? 0 : $this->percent;

            $this->over    = ($this->format === "-") ? -$percent : $percent;
            $this->under   = ($this->format === "+") ? -$percent : $percent;
        }
    }
}
