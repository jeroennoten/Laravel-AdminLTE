<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class SmallBox extends Component
{
    public $bg;
    public $icon;
    public $title;
    public $text;
    public $url;
    public $urlText;
    public $loading;
    public $id;

    public function __construct(
        $bg = 'info', $icon = 'fas fa-star', $title, $id = null,
        $text, $url = '#', $urlText = null,
        $loading = false)
    {
        $this->id = $id;
        $this->bg = $bg;
        $this->icon = $icon;
        $this->title = $title;
        $this->text = $text;
        $this->url = $url;
        $this->urlText = $urlText;
        $this->loading = $loading;
    }

    public function urlTextLine()
    {
        return ($this->urlText == null) ? 'More Info' : $this->urlText;
    }

    public function render()
    {
        return view('adminlte::small-box');
    }
}
