<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class Progress extends Component
{
    public $size, $bg, $stripped, $vertical;
    public $value;
    
    public function __construct(
        $size = null, $bg = 'info', 
        $value, $stripped = false, $vertical = false)
    {
        $this->bg = $bg;
        $this->size = $size;
        $this->value = $value;
        $this->stripped = $stripped;
        $this->vertical = $vertical;
    }

    public function barsize()
    {
        return !is_null($this->size) ? 'progress-'.$this->size : null;
    }

    public function render()
    {
        return view('adminlte::progress');
    }
}