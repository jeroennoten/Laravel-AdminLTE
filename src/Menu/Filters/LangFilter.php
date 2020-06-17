<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Translation\Translator;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class LangFilter implements FilterInterface
{
    /**
     * The translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Transforms a menu item. Makes the item translations.
     *
     * @param mixed $item A menu item
     * @param Builder $builder A menu builder instance
     * @return mixed The transformed menu item
     */
    public function transform($item, Builder $builder)
    {
        // Translate the header attribute.

        if (isset($item['header'])) {
            $item['header'] = $this->getTranslation($item['header']);
        }

        // Translate the text attribute.

        if (isset($item['text'])) {
            $item['text'] = $this->getTranslation($item['text']);
        }

        // Translate the label attribute.

        if (isset($item['label'])) {
            $item['label'] = $this->getTranslation($item['label']);
        }

        return $item;
    }

    /**
     * Gets the translation for a given key.
     *
     * @param string $key The key to translate
     * @return string The translation
     */
    protected function getTranslation($key)
    {
        // Check for a translation.

        if ($this->translator->has('menu.'.$key)) {
            return $this->translator->get('menu.'.$key);
        } elseif ($this->translator->has('adminlte::menu.'.$key)) {
            return $this->translator->get('adminlte::menu.'.$key);
        }

        // When no translation available, return the original key.

        return $key;
    }
}
