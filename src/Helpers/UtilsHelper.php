<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

class UtilsHelper
{
    /**
     * Apply an HTML entity decoder to the specified string.
     *
     * @param  string  $value
     * @return string
     */
    public static function applyHtmlEntityDecoder($value)
    {
        return isset($value) ? html_entity_decode($value) : $value;
    }

    /**
     * Checks whether the provided set of classes already defines a bottom
     * margin utility (for example 'mb-0' or 'my-4'). Note the Bootstrap
     * spacing utilities are declared with '!important' and with the same
     * specificity, so a default margin can not be overridden by adding
     * another one, the order of the stylesheet decides the winner.
     *
     * @param  string|null  $classes  The set of classes to check
     * @return bool
     */
    public static function hasBottomMarginClass($classes)
    {
        return (bool) preg_match('/(^|\s)m[by]-(auto|[0-5])(\s|$)/', (string) $classes);
    }

    /**
     * The colors of the AdminLTE v4 extended palette, as provided by the
     * optional 'adminlte-colors.css' stylesheet.
     *
     * @var array
     */
    protected static $extendedColors = [
        'amber', 'fuchsia', 'graphite', 'indigo', 'midnight', 'navy', 'olive',
        'orange', 'pink', 'sky', 'slate', 'steel', 'teal', 'violet',
    ];

    /**
     * The colors of the AdminLTE v3 palette, as provided by the optional
     * 'adminlte-colors-v3.css' stylesheet.
     *
     * @var array
     */
    protected static $extendedColorsV3 = [
        'blue', 'cyan', 'fuchsia', 'gray', 'gray-dark', 'green', 'indigo',
        'lightblue', 'lime', 'maroon', 'navy', 'olive', 'orange', 'pink',
        'purple', 'red', 'teal', 'yellow',
    ];

    /**
     * Gets the set of colors provided by the enabled extended palette. It
     * returns an empty array when the extended colors are disabled.
     *
     * @return array
     */
    public static function getExtendedColors()
    {
        if (! config('adminlte.assets.extended_colors', false)) {
            return [];
        }

        return config('adminlte.assets.extended_colors_v3_aliases', false)
            ? self::$extendedColorsV3
            : self::$extendedColors;
    }
}
