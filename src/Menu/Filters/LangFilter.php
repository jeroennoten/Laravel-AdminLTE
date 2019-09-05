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

    private function trans($identifier)
    {
        return method_exists($this->langGenerator, 'trans') ? $this->langGenerator->trans($identifier) : $this->langGenerator->get($identifier);
    }

    public function transform($item, Builder $builder)
    {
        if (isset($item['header'])) {
            $item['header'] = ($this->langGenerator->has('adminlte::menu.'.$item['header'])) ? $this->trans('adminlte::menu.'.$item['header']) : $item['header'];
        }
        if (isset($item['text'])) {
            $item['text'] = ($this->langGenerator->has('adminlte::menu.'.$item['text'])) ? $this->trans('adminlte::menu.'.$item['text']) : $item['text'];
        }

        return $item;
    }
}
