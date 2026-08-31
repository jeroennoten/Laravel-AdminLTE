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
}
