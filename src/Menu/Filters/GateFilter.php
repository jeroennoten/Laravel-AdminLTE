<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class GateFilter implements FilterInterface
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {
        if (! $this->isVisible($item)) {
            return false;
        }

        return $item;
    }

    protected function isVisible($item)
    {
        if (! isset($item['can'])) {
            return true;
        }

        $args = [];

        if (isset($item['model'])) {
            $args = $item['model'];
        }

        if (! is_array($item['can'])) {
            return $this->gate->allows($item['can'], $args);
        }

        foreach ($item['can'] as $can) {
            if ($this->gate->allows($can, $args)) {
                return true;
            }
        }

        return false;
    }
}
