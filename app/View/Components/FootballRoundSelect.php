<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class FootballRoundSelect extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construc()
    {

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $current_round = DB::table('football_matches')->latest()->first()?->round;

        return view('components.form.football-round-select', compact("current_round"));
    }
}
