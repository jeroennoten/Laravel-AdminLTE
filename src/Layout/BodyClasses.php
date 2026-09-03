<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

class BodyClasses
{
    /**
     * Makes the set of classes of the body tag.
     *
     * @return array
     */
    public static function make(): array
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
    public static function isFixedNavbarEnabled(): bool
    {
        return self::isFixedSectionEnabled('navbar');
    }

    /**
     * Checks whether the footer is configured as fixed.
     *
     * @return bool
     */
    public static function isFixedFooterEnabled(): bool
    {
        return self::isFixedSectionEnabled('footer');
    }

    /**
     * Makes the set of classes related to the layout configuration.
     *
     * @return array
     */
    protected static function makeLayoutClasses(): array
    {
        $classes = [];

        // The fixed sidebar is not compatible with the topnav layout.

        if (! Layout::isTopnavEnabled() && config('adminlte.layout_fixed_sidebar', true)) {
            $classes[] = Tokens::LAYOUT_FIXED;
        }

        if (self::isFixedNavbarEnabled()) {
            $classes[] = Tokens::FIXED_NAVBAR;
        }

        if (self::isFixedFooterEnabled()) {
            $classes[] = Tokens::FIXED_FOOTER;
        }

        // The compact mode belongs to the body and not to the wrapper: two of
        // its rules compound the token with the sidebar ones on a single
        // element, and those live here. Every other rule is a descendant
        // selector, so it keeps matching from the body too.

        if (config('adminlte.layout_compact', false)) {
            $classes[] = Tokens::COMPACT_MODE;
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
    protected static function isFixedSectionEnabled($section): bool
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
    protected static function makeCustomClasses(): array
    {
        $cfg = config('adminlte.classes_body', 'bg-body-tertiary');

        return is_string($cfg) && ! empty($cfg) ? [$cfg] : [];
    }
}
