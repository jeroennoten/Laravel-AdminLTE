<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputFile extends Component
{
    public $id, $name, $label, $placeholder;
    public $topclass, $inputclass;
    public $disabled, $required, $multiple;

    public function __construct(
            $id = null, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $disabled = false, $required = false, $multiple = false
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->multiple = $multiple;
    }

    public function render()
    {
        return view('adminlte::input-file');
    }
}