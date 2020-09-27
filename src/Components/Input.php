<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $type, $id, $name, $label, $placeholder;
    public $topclass, $inputclass;
    public $value, $disabled, $required;
    public $step, $max, $maxlength, $pattern;

    public function __construct(
            $type = 'text', $id = null, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $value = null, $disabled = false, $required = false,
            $step = null, $max = null, $maxlength = null, $pattern = null
        )
    {
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->value = $value;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->step = $step;
        $this->max = $max;
        $this->maxlength = $maxlength;
        $this->pattern = $pattern;
    }

    public function render()
    {
        return view('adminlte::input');
    }
}