<?php


namespace JeroenNoten\LaravelAdminLte\Menu;


class Builder
{
    public $menu = [];

    public function add(...$item)
    {
        if (!empty($item)) {
            array_push($this->menu, ...$item);
        }
    }
}