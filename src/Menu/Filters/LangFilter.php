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
            $item['header'] = $this->getTranslation($item['header']) ?? $item['header'];
        }
        if (isset($item['text'])) {
            $item['text'] = $this->getTranslation($item['text']) ?? $item['text'];
        }

        return $item;
    }

    protected function getTranslation($item)
    {
        if ($this->langGenerator->has('menu.'.$item)) {
            return $this->langGenerator->get('menu.'.$item);
        } elseif ($this->langGenerator->has('adminlte::menu.'.$item)) {
            return $this->langGenerator->get('adminlte::menu.'.$item);
        }

        return $item;
    }
}
