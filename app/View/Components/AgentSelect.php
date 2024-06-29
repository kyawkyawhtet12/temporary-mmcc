<?php

namespace App\View\Components;

use App\Models\Agent;
use Illuminate\View\Component;

class AgentSelect extends Component
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
        $agents = Agent::pluck('name', 'id');

        return view('components.form.agent-select', compact("agents"));
    }
}
