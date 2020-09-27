<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Callout extends Component
{
    public $type, $title;

    public function __construct($type = 'info', $title = null)
    {
        $this->type = $type;
        $this->title = $title;
    }

    public function render()
    {
        return view('adminlte::callout');
    }
}