<?php


namespace JeroenNoten\LaravelAdminLte\Menu;


class Builder
{
    public $menu = [];

    public function add()
    {
        $items = func_get_args();

        foreach($items as $item) {
            array_push($this->menu, $item);
        }
    }
}