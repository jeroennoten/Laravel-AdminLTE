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
     * @param  Translator  $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->itemProperties = ['header', 'text', 'label'];
    }

    /**
     * Transforms a menu item. Makes the item translations.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        // Translate the menu item properties.

        foreach ($this->itemProperties as $prop) {
            // Check if the property exists for the item.

            if (! isset($item[$prop])) {
                continue;
            }

            // Check if the property value is valid for be translated.

            if (is_array($item[$prop])) {
                $params = $item[$prop][1] ?? [];
                $params = is_array($params) ? $params : [];
                $item[$prop] = $this->getTranslation($item[$prop][0], $params);
            } elseif (is_string($item[$prop])) {
                $item[$prop] = $this->getTranslation($item[$prop]);
            }
        }

        return $item;
    }

    /**
     * Gets the translation for a given key.
     *
     * @param  string  $key  The key to translate
     * @param  array  $params  The additional translation params
     * @return string The translation
     */
    protected function getTranslation($key, $params = [])
    {
        // Check for a translation.

        if ($this->translator->has('menu.'.$key)) {
            return $this->translator->get('menu.'.$key, $params);
        } elseif ($this->translator->has('adminlte::menu.'.$key)) {
            return $this->translator->get('adminlte::menu.'.$key, $params);
        }

        // When no translation available, return the original key.

        return $key;
    }
}
