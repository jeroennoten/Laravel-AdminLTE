<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputSlider extends Component
{
    public $id;
    public $name;
    public $label;
    public $topclass;
    public $inputclass;
    public $value;
    public $disabled;
    public $required;
    public $min;
    public $max;
    public $step;
    public $vertical;
    public $tick;
    public $ticks;
    public $tickLabels;
    public $color;

    public function __construct(
            $id, $name = null,
            $label = 'Input Label',
            $topclass = null, $inputclass = null,
            $value = null, $disabled = false, $required = false,
            $min = 0, $max = 100, $step = 1, $vertical = false,
            $tick = false, $ticks = null, $tickLabels = null,
            $color = 'blue'
        ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->vertical = $vertical;
        $this->tick = $tick;
        $this->ticks = $ticks;
        $this->tickLabels = $tickLabels;
        $this->color = $color;
    }

    public function render()
    {
        return view('adminlte::input-slider');
    }
}
