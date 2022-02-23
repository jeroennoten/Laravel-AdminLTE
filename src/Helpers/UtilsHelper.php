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
}
