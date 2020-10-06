<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $topclass;
    public $inputclass;
    public $value;
    public $disabled;
    public $required;
    public $step;
    public $max;
    public $maxlength;
    public $pattern;

    public function __construct(
            $type = 'text', $id = null, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $value = null, $disabled = false, $required = false,
            $step = null, $max = null, $maxlength = null, $pattern = null
        ) {
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
        return view('adminlte::components.input');
    }
}
