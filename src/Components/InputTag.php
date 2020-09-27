<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputTag extends Component
{
    public $id, $name, $label, $max;
    public $topclass, $inputclass;
    public $disabled, $required;

    public function __construct(
            $id = null, $name = null,
            $label = 'Input Label',
            $topclass = null, $inputclass = null,
            $disabled = false, $required = false, $max = 10
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->max = $max;
    }

    public function render()
    {
        return view('adminlte::input-tag');
    }
}