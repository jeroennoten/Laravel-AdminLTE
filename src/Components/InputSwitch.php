<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputSwitch extends Component
{
    public $id, $name, $label;
    public $topclass, $inputclass;
    public $checked, $disabled, $required;

    public function __construct(
            $id = 'checkbox', $name = null,
            $label = 'Input Label',
            $topclass = null, $inputclass = null,
            $checked = false, $disabled = false, $required = false
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->checked = $checked;
        $this->required = $required;
        $this->disabled = $disabled;
    }

    public function render()
    {
        return view('adminlte::input-switch');
    }
}