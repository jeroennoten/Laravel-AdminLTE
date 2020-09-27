<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputDate extends Component
{
    public $id, $name, $label, $placeholder;
    public $topclass, $inputclass;
    public $value, $disabled, $required;
    public $format;

    public function __construct(
            $id, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $value = null, $disabled = false, $required = false,
            $format = 'YYYY-MM-DD'
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
        $this->format = $format;
    }

    public function render()
    {
        return view('adminlte::input-date');
    }
}