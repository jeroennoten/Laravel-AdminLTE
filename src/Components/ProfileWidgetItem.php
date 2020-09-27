<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class ProfileWidgetItem extends Component
{
    public $col;
    public $title;
    public $text;

    public function __construct($col = '4', $title, $text)
    {
        $this->col = $col;
        $this->title = $title;
        $this->text = $text;
    }

    public function render()
    {
        return view('adminlte::profile-widget-item');
    }
}
