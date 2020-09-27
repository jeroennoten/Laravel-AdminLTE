<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Option extends Component
{
    public $value;
    public $icon;
    public $content;
    public $selected;
    public $disabled;

    public function __construct(
        $value = null, $icon = false, $content = null,
        $selected = false, $disabled = false)
    {
        $this->value = $value;
        $this->icon = $icon;
        $this->content = $content;
        $this->selected = $selected;
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('adminlte::option');
    }
}
