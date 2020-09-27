<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class DateRange extends Component
{
    public $topclass, $inputclass, $title, $icon, $id;
    public $init;
    public $callback;

    public function __construct(
        $id, $topclass = null, $title = 'Filter Range', $icon = 'far fa-calendar-alt',
        $init = 2, $callback = null, $inputclass = null
        )
    {
        $this->id = $id;
        $this->topclass = $topclass;
        $this->inputclass = $inputclass;
        $this->title = $title;
        $this->icon = $icon;
        $this->init = $init;
        $this->callback = $callback;
    }

    public function initiator()
    {
        switch($this->init)
        {
            case 0 : $s = "startDate: moment(), endDate: moment()"; break;
            case 1 : $s = "startDate: moment().subtract(1, 'days'), endDate: moment().subtract(1, 'days')"; break;
            case 2 : $s = "startDate: moment().subtract(6, 'days'), endDate: moment()"; break;
            case 3 : $s = "startDate: moment().subtract(29, 'days'), endDate: moment()"; break;
            case 4 : $s = "startDate: moment().startOf('month'), endDate: moment().endOf('month')"; break;
            case 5 : $s = "startDate: moment().subtract(1, 'month').startOf('month'), endDate: moment().subtract(1, 'month').endOf('month')"; break;
        }
        return $s;
    }

    public function render()
    {
        return view('adminlte::date-range');
    }
}