<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Support\Facades\Lang;

class LangFilter implements FilterInterface
{
    /**
     * The array of menu item's properties that can be translated.
     *
     * @var array
     */
    protected $itemProperties;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->itemProperties = ['header', 'text', 'label'];
    }

    /**
     * Transforms a menu item. Makes the translations on the expected item
     * properties.
     *
     * @param  array  $item  A menu item
     * @return array
     */
    public function transform($item)
    {
        // Translate the expected menu item properties.

        foreach ($this->itemProperties as $prop) {
            // Check if the property exists in the item.

            if (empty($item[$prop])) {
                continue;
            }

            // Check if the property value is valid to be translated.

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
     * @param  array  $params  The additional translation parameters
     * @return string
     */
    protected function getTranslation($key, $params = [])
    {
        // Check for a translation. Note we first check if translations are
        // available in a "menu.php" file, then we check for translations in
        // the published language resources (under the adminlte namespace).

        if (Lang::has("menu.{$key}")) {
            return Lang::get("menu.{$key}", $params);
        } elseif (Lang::has("adminlte::menu.{$key}")) {
            return Lang::get("adminlte::menu.{$key}", $params);
        }

        // When there is no translation available, return the original key.

        return $key;
    }
}
