<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public $bg;
    public $icon;
    public $title;
    public $text;
    public $full;
    public $grad;
    public $id;
    public $progress;
    public $comment;

    public function __construct(
        $bg = 'info', $icon = 'fas fa-star', $id = null,
        $title, $text, $full = false, $grad = false,
        $progress = false, $comment = false)
    {
        $this->id = $id;
        $this->bg = $bg;
        $this->icon = $icon;
        $this->title = $title;
        $this->text = $text;
        $this->full = $full;
        $this->grad = $grad;
        $this->progress = $progress;
        $this->comment = $comment;
    }

    public function background()
    {
        return $this->full ? ($this->grad ? 'bg-gradient-' : 'bg-').$this->bg : '';
    }

    public function foreground()
    {
        return ! $this->full ? ($this->grad ? 'bg-gradient-' : 'bg-').$this->bg : '';
    }

    public function render()
    {
        return view('adminlte::components.info-box');
    }
}
