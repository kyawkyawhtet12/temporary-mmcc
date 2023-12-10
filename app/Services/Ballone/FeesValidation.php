<?php

namespace App\Services\Ballone;

use App\Models\FootballMatch;

use function PHPUnit\Framework\isNull;

class FeesValidation
{
    protected $regex = "/[a-z]/i";
    protected $regex_format = "/^[1-9][0-9]*$/";

    public function handle($request)
    {
        $request->validate([
            'match_id'  => 'required',
            'home_body' => 'required_without:away_body',
            'away_body' => 'required_without:home_body',
            'goals'     => 'required'
        ]);

        $match = $request->match_id;

        if(!$this->check_match($match)){
            throw new \Exception("* Match is not found.");
        }

        if(!$this->checkFees(($request->home_body ?? $request->away_body))){
            throw new \Exception("* Invalid Body Fees");
        }

        $goalFees = $request->goals;

        if(!$this->checkFees($goalFees)){
            throw new \Exception("* Invalid Goal Fees");
        }
    }

    public function multipleHandle($request)
    {
        foreach( $request->home_body as $x => $home_body ){

            $bodyFees = ($request->home_body[$x] ?? $request->away_body[$x]);

            if($bodyFees && !$this->checkFees($bodyFees)){
                throw new \Exception("* Body fees is invalid");
            }

            $goalFees = $request->goals[$x];

            if($goalFees && !$this->checkFees($goalFees)){
                throw new \Exception("* Goal fees is invalid");
            }
        }
    }

    protected function check_match($match_id)
    {
        $match = FootballMatch::find($match_id);
        return ( $match ) ? true : false;
    }

    public function checkFees($fees)
    {
        $format = match (true) {
            (str_contains($fees, '+')) => '+',
            (str_contains($fees, '-')) => '-',
            (str_contains($fees, '=')) => '=',
            default => 'error',
        };

        if( $format == 'error' ){
            return false;
        }

        if(preg_match($this->regex, $fees)) {
            return false;
        }

        $arr = explode($format, $fees);

        list($limit, $percent) = $arr + [];

        $percent = ($percent === '') ? 100 : $percent;

        if(count($arr) != 2){
            return false;
        }

        if(!preg_match($this->regex_format, $percent)) {
            return false;
        }

        if((!is_numeric($limit) && !empty($limit)) && $limit != '=') {
            return false;
        }

        if((!is_numeric($percent) && !empty($percent))) {
            return false;
        }

        if( $percent > 100 || $percent < 10 ){
            return false;
        }

        return true;
    }
}
