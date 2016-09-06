<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class GateFilter implements Filter
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {
        return $this->isVisible($item) ? $item : false;
    }

    protected function isVisible($item)
    {
        return ! isset($item['can']) || $this->gate->allows($item['can']);
    }
}
