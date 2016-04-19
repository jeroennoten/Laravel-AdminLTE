<?php


namespace JeroenNoten\LaravelAdminLte;


use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class AdminLte
{
    protected $menu;
    
    private $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function menu()
    {
        if (!$this->menu) {
            $this->menu = $this->buildMenu();
        }

        return $this->menu;
    }

    protected function buildMenu()
    {
        $builder = new Builder;
        
        $this->events->fire(new BuildingMenu($builder));

        return $builder->menu;
    }
}