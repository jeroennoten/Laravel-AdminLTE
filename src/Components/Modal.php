<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id, $title, $size, $centered;
    public $index;

    public function __construct(
        $title, $size = null, $id, 
        $centered = true, $index = 1)
    {
        $this->id = $id;
        $this->title = $title;
        $this->centered = $centered;
        $this->size = $size;
        $this->index = $index;
    }

    public function modalsize()
    {
        return !is_null($this->size) ? 'modal-'.$this->size : '';
    }

    public function zindex()
    {
        return $this->index * 1050;
    }

    public function render()
    {
        return view('adminlte::modal');
    }
}