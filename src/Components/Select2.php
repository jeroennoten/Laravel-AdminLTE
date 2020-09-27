<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Select2 extends Component
{
    public $id;
    public $name;
    public $label;
    public $topclass;
    public $inputclass;
    public $disabled;
    public $required;
    public $multiple;

    public function __construct(
            $id, $name = null,
            $label = 'Input Label',
            $topclass = null, $inputclass = null,
            $disabled = false, $required = false, $multiple = false
        ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->multiple = $multiple;
    }

    public function render()
    {
        return view('adminlte::select2');
    }
}
