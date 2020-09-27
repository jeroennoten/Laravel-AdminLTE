<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class TextEditor extends Component
{
    public $id, $name, $label, $placeholder;
    public $topclass, $inputclass;
    public $body, $disabled, $required;
    public $height, $fonts;
    public $def_fonts = ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Impact', 'Montserrat',  'Open Sans', 'Ubuntu', 'Rajdhani'];

    public function __construct(
            $id, $name = null,
            $label = 'Input Label', $placeholder = null,
            $topclass = null, $inputclass = null,
            $body = null, $disabled = false, $required = false,
            $height = 500, $fonts = null
        )
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->body = $body;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->height = $height;
        $this->fonts = $fonts;
    }

    public function fontarray()
    {
        return $this->fonts == null ? json_encode($this->def_fonts) : $this->fonts;
    }

    public function render()
    {
        return view('adminlte::text-editor');
    }
}