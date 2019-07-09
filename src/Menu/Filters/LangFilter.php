<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Translation\Translator;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class LangFilter implements FilterInterface
{
    protected $langGenerator;

    public function __construct(Translator $langGenerator)
    {
        $this->langGenerator = $langGenerator;
    }

    public function transform($item, Builder $builder)
    {
        if (isset($item['header'])) {
            $item['header'] = ($this->langGenerator->has('menu.'.$item['header'])) ? $this->langGenerator->trans('menu.'.$item['header']) : $item['header'];
        }
        if (isset($item['text'])) {
            $item['text'] = ($this->langGenerator->has('menu.'.$item['text'])) ? $this->langGenerator->trans('menu.'.$item['text']) : $item['text'];
        }

        return $item;
    }
}
