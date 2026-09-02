<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use Illuminate\Support\Facades\View;

class Sidebar
{
    /**
     * Makes the set of classes that the sidebar adds to the body tag. Note the
     * topnav layout has no sidebar, so it adds none of them.
     *
     * @return array
     */
    public static function makeBodyClasses()
    {
        if (Layout::isTopnavEnabled()) {
            return [];
        }

        $classes = [];
        $expand = Tokens::sidebarExpand(config('adminlte.sidebar_expand', 'lg'));

        if (isset($expand)) {
            $classes[] = $expand;
        }

        if (self::isMiniEnabled()) {
            $classes[] = Tokens::SIDEBAR_MINI;
        }

        if (self::isCollapsed()) {
            $classes[] = Tokens::SIDEBAR_COLLAPSE;
        }

        if (config('adminlte.sidebar_without_hover', false)) {
            $classes[] = Tokens::SIDEBAR_WITHOUT_HOVER;
        }

        return $classes;
    }

    /**
     * Makes the set of classes of the sidebar element.
     *
     * @return array
     */
    public static function makeClasses()
    {
        $classes = [Tokens::SIDEBAR];
        $cfg = config('adminlte.classes_sidebar', 'bg-body-secondary shadow');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        return $classes;
    }

    /**
     * Makes the set of classes of the sidebar navigation menu element.
     *
     * @return array
     */
    public static function makeNavClasses()
    {
        $classes = [Tokens::NAV, Tokens::SIDEBAR_MENU, Tokens::NAV_COLUMN];

        // The style variants that AdminLTE defines for the sidebar menu.

        $variants = [
            'sidebar_nav_compact' => Tokens::NAV_COMPACT,
            'sidebar_nav_indent' => Tokens::NAV_INDENT,
            'sidebar_nav_pills' => Tokens::NAV_PILLS,
        ];

        foreach ($variants as $option => $token) {
            if (config("adminlte.{$option}", false)) {
                $classes[] = $token;
            }
        }

        $cfg = config('adminlte.classes_sidebar_nav', '');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        return $classes;
    }

    /**
     * Makes the set of data attributes of the sidebar element.
     *
     * @return array
     */
    public static function makeAttributes()
    {
        $attrs = [];
        $theme = config('adminlte.sidebar_theme', 'dark');

        if (in_array($theme, ['light', 'dark'], true)) {
            $attrs[] = Tokens::COLOR_MODE_ATTRIBUTE.'="'.$theme.'"';
        }

        // The AdminLTE push menu plugin remembers the collapsed state of the
        // sidebar between page loads through this attribute.

        if (config('adminlte.sidebar_collapse_remember', false)) {
            $attrs[] = Tokens::SIDEBAR_PERSISTENCE_ATTRIBUTE.'="true"';
        }

        // The plugin uses its own breakpoint (991.98 pixels) to tell a desktop
        // viewport from a mobile one. This attribute overrides it.

        $breakpoint = config('adminlte.sidebar_breakpoint');

        if (is_numeric($breakpoint)) {
            $attrs[] = Tokens::SIDEBAR_BREAKPOINT_ATTRIBUTE.'="'.$breakpoint.'"';
        }

        return $attrs;
    }

    /**
     * Checks whether the sidebar mini mode is enabled. Note the legacy
     * breakpoint tokens are still accepted and mean 'enabled'.
     *
     * @return bool
     */
    protected static function isMiniEnabled()
    {
        $cfg = config('adminlte.sidebar_mini', true);

        if (is_string($cfg)) {
            return in_array($cfg, Tokens::LEGACY_SIDEBAR_MINI_TOKENS, true);
        }

        return (bool) $cfg;
    }

    /**
     * Checks whether the sidebar starts on its collapsed state.
     *
     * @return bool
     */
    protected static function isCollapsed()
    {
        return (bool) config('adminlte.sidebar_collapse', false)
            || ! empty(View::getSection('sidebar_collapse'));
    }
}
