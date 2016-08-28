<?php

namespace JeroenNoten\LaravelAdminLte;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use Illuminate\Contracts\Auth\Access\Gate;

class AdminLte
{
    protected $menu;

    private $events;

    private $urlGenerator;

    private $activeChecker;

    private $gate;

    public function __construct(
        Dispatcher $events,
        UrlGenerator $urlGenerator,
        ActiveChecker $activeChecker,
        Gate $gate
    ) {
        $this->events = $events;
        $this->urlGenerator = $urlGenerator;
        $this->activeChecker = $activeChecker;
        $this->gate = $gate;
    }

    public function menu()
    {
        if (! $this->menu) {
            $this->menu = $this->buildMenu();
        }

        return $this->menu;
    }

    protected function buildMenu()
    {
        $builder = new Builder($this->urlGenerator, $this->activeChecker, $this->gate);

        $this->events->fire(new BuildingMenu($builder));

        return $builder->menu;
    }
}
