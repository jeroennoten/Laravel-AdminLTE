<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\ActiveChecker;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class SubmenuFilter implements FilterInterface
{
    private $activeChecker;

    public function __construct(ActiveChecker $activeChecker)
    {
        $this->activeChecker = $activeChecker;
    }

    public function transform($item, Builder $builder)
    {
        if (isset($item['submenu'])) {
            $item['submenu'] = $builder->transformItems($item['submenu']);
            $item['submenu_open'] = $this->activeChecker->isActive($item);
            $item['submenu_classes'] = $this->makeSubmenuClasses($item);
            $item['submenu_class'] = implode(' ', $item['submenu_classes']);
        }

        return $item;
    }

    protected function makeSubmenuClasses($item)
    {
        $classes = ['has-treeview'];

        if ($item['submenu_open']) {
            $classes[] = 'menu-open';
        }

        return $classes;
    }
}
