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
            $item['header'] = $this->langGenerator->trans($item['header']);
        }
        if (isset($item['text'])) {
            $item['text'] = $this->langGenerator->trans($item['text']);
        }

        return $item;
    }
}
