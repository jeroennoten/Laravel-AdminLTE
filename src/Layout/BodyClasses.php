<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

class BodyClasses
{
    /**
     * Makes the set of classes of the body tag.
     *
     * @return array
     */
    public static function make()
    {
        return array_unique(array_merge(
            self::makeLayoutClasses(),
            Sidebar::makeBodyClasses(),
            self::makeCustomClasses()
        ));
    }

    /**
     * Checks whether the navbar is configured as fixed.
     *
     * @return bool
     */
    public static function isFixedNavbarEnabled()
    {
        return self::isFixedSectionEnabled('navbar');
    }

    /**
     * Checks whether the footer is configured as fixed.
     *
     * @return bool
     */
    public static function isFixedFooterEnabled()
    {
        return self::isFixedSectionEnabled('footer');
    }

    /**
     * Makes the set of classes related to the layout configuration.
     *
     * @return array
     */
    protected static function makeLayoutClasses()
    {
        $classes = [];

        // The fixed sidebar is not compatible with the topnav layout.

        if (! Layout::isTopnavEnabled() && config('adminlte.layout_fixed_sidebar', false)) {
            $classes[] = Tokens::LAYOUT_FIXED;
        }

        if (self::isFixedNavbarEnabled()) {
            $classes[] = Tokens::FIXED_NAVBAR;
        }

        if (self::isFixedFooterEnabled()) {
            $classes[] = Tokens::FIXED_FOOTER;
        }

        return $classes;
    }

    /**
     * Checks whether the fixed mode is enabled for the specified layout
     * section. Note AdminLTE v4 does not support responsive fixed modes, so
     * an array configuration is considered enabled when any of its values is
     * enabled.
     *
     * @param  string  $section  The layout section (navbar or footer)
     * @return bool
     */
    protected static function isFixedSectionEnabled($section)
    {
        $cfg = config("adminlte.layout_fixed_{$section}", false);

        if (is_array($cfg)) {
            return in_array(true, $cfg, true);
        }

        return (bool) $cfg;
    }

    /**
     * Makes the set of classes coming from the package configuration.
     *
     * @return array
     */
    protected static function makeCustomClasses()
    {
        $cfg = config('adminlte.classes_body', '');

        return is_string($cfg) && ! empty($cfg) ? [$cfg] : [];
    }
}
