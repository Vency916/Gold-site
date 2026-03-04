<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $suppressGlobalNav;

    public function __construct($suppressGlobalNav = false)
    {
        $this->suppressGlobalNav = $suppressGlobalNav;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
