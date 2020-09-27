<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Submit extends Component
{
    public $type;
    public $label;
    public $icon;
    public $topclass;
    public $inputclass;

    public function __construct(
        $type = 'primary', $label = 'Submit', $icon = 'fas fa-save',
        $topclass = 'text-center', $inputclass = null)
    {
        $this->type = $type;
        $this->icon = $icon;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->label = $label;
    }

    public function render()
    {
        return view('adminlte::submit');
    }
}
