<?php

namespace App\Traits;

trait BalloneFeesResult
{
    public function getResult($type)
    {
        $attr = [
            'home'  => check_plus_format($this->home),
            'away'  => check_plus_format($this->away),
            'over'  => check_plus_format($this->over),
            'under' => check_plus_format($this->under)
        ];

        return $attr[$type] ?? '0';
    }
}
