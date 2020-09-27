<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class ProfileFlat extends Component
{
    public $bg, $img, $name, $desc;

    public function __construct($bg = 'info', $img, $name, $desc)
    {
        $this->bg = $bg;
        $this->img = $img;
        $this->name = $name;
        $this->desc = $desc;
    }

    public function background()
    {
        return 'bg-'.$this->bg;
    }

    public function render()
    {
        return view('adminlte::profile-flat');
    }
}