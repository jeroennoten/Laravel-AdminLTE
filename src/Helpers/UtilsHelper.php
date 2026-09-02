<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

use JeroenNoten\LaravelAdminLte\Layout\Palette;

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
        return (bool) preg_match('/(^|\s)m[by]-((sm|md|lg|xl|xxl)-)?(auto|[0-5])(\s|$)/', (string) $classes);
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
     * The colors whose 'text-bg-*' utility paints a dark text, since their
     * background is light enough. Note the v3 names are part of the set, since
     * they are real colors when the v3 alias stylesheet is loaded.
     *
     * @var array
     */
    protected static $darkTextColors = [
        'info', 'warning', 'light', 'cyan', 'yellow',
    ];

    /**
     * The additional colors of the v3 palette that get a dark text once the
     * WCAG AA contrast correction is applied over that palette.
     *
     * @var array
     */
    protected static $darkTextColorsOnContrastAa = [
        'blue', 'fuchsia', 'green', 'lightblue', 'olive', 'pink', 'teal',
    ];

    /**
     * Checks whether a color paints a dark text over its own background. It's
     * the predicate behind the contrast of any element placed over a themed
     * background (links, close buttons, ...).
     *
     * @param  string|null  $color  The theme color name
     * @return bool
     */
    public static function hasDarkText($color)
    {
        if (empty($color) || ! is_string($color)) {
            return false;
        }

        if (in_array($color, self::$darkTextColors, true)) {
            return true;
        }

        return Palette::getContrast() === 'aa'
            && in_array($color, self::$darkTextColorsOnContrastAa, true);
    }

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
