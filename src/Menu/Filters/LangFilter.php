<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Translation\Translator;

class LangFilter implements FilterInterface
{
    /**
     * The translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * The array of menu item properties to translate.
     *
     * @var array
     */
    protected $itemProperties;

    /**
     * Constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->itemProperties = ['header', 'text', 'label'];
    }

    /**
     * Transforms a menu item. Makes the item translations.
     *
     * @param array $item A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        // Translate the menu item properties.

        foreach ($this->itemProperties as $prop) {
            if (isset($item[$prop]) && is_string($item[$prop])) {
                $item[$prop] = $this->getTranslation($item[$prop]);
            }
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
