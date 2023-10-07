<?php

namespace App\Traits;

trait BalloneFeesResult
{
    public function check_result($type)
    {
       $results = [
            'home'  => $this->get_format('home'),
            'away'  => $this->get_format('away'),
            'over'  => $this->get_format('over'),
            'under' => $this->get_format('under'),
       ];

        return $results[$type];
    }

    public function get_format($type)
    {
        $error = ( $type == "home" || $type == "away") ? $this->body_error : $this->goal_error;

        $value = $this->get_result_value($type);

        return ($error) ? $this->input_form($type, $value) : $value;
    }

    public function get_result_value($type)
    {
        $attr = [
            'home'  => check_plus_format($this->home),
            'away'  => check_plus_format($this->away),
            'over'  => check_plus_format($this->over),
            'under' => check_plus_format($this->under),
        ];

        return $attr[$type];
    }

    public function input_form($name, $value)
    {
        return "<input type='text' name='$name' value='$value' class='form-control' required>";
    }

    public function check_button()
    {
        if( $this->body_error || $this->goal_error ){
            return "<button type='submit' class='btn btn-success btn-sm'> Refresh </button>";
        }

        return "";
    }
}
