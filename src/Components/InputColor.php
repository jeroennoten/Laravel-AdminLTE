<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputColor extends Component
{
    public $id, $name, $label, $placeholder;
    public $topclass, $inputclass;
    public $value, $disabled, $required;

    public function __construct(
            $id, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $value = null, $disabled = false, $required = false
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->value = $value;
        $this->required = $required;
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('adminlte::input-color');
    }
}