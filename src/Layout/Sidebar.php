<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use Illuminate\Support\Facades\View;

class Sidebar
{
    /**
     * Gets the name of the expand breakpoint of the sidebar. A configured
     * 'sidebar_breakpoint' width wins over the 'sidebar_expand' option when it
     * matches one of the widths AdminLTE ships, so both the media queries and
     * the push menu script agree on where the sidebar turns into an overlay.
     * A width AdminLTE has no stylesheet for is ignored, since honoring it
     * would leave them disagreeing.
     *
     * @return string|null
     */
    protected static function expandBreakpoint(): ?string
    {
        $fromWidth = Tokens::sidebarBreakpointName(
            config('adminlte.sidebar_breakpoint')
        );

        return $fromWidth ?? config('adminlte.sidebar_expand', 'lg');
    }

    /**
     * Makes the set of classes that the sidebar adds to the body tag. Note the
     * topnav layout has no sidebar, so it adds none of them.
     *
     * @return array
     */
    public static function makeBodyClasses(): array
    {
        if (Layout::isTopnavEnabled()) {
            return [];
        }

        $classes = [];
        $expand = Tokens::sidebarExpand(self::expandBreakpoint());

        if (isset($expand)) {
            $classes[] = $expand;
        }

        if (self::isMiniEnabled()) {
            $classes[] = Tokens::SIDEBAR_MINI;
        }

        if (self::isCollapsed()) {
            $classes[] = Tokens::SIDEBAR_COLLAPSE;
        }

        // The compact and the indent variants belong here and not to the menu
        // element: AdminLTE compounds them with the tokens above on a single
        // element, and then reaches the sidebar as a descendant. Their
        // remaining rules are descendant selectors, so they keep matching from
        // the body. Note the pills variant is not one of them, see the
        // {@see makeNavClasses()} method.

        $variants = [
            'sidebar_nav_compact' => Tokens::NAV_COMPACT,
            'sidebar_nav_indent' => Tokens::NAV_INDENT,
        ];

        foreach ($variants as $option => $token) {
            if (config("adminlte.{$option}", false)) {
                $classes[] = $token;
            }
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
    public static function makeClasses(): array
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
    public static function makeNavClasses(): array
    {
        $classes = [Tokens::NAV, Tokens::SIDEBAR_MENU, Tokens::NAV_COLUMN];

        // The pills variant is the plain Bootstrap one: it is never compounded
        // with a layout token, and its two rules ('.nav-pills .nav-link' and
        // '.nav-pills .show > .nav-link') are descendant selectors. So it
        // belongs to the menu element. On the body it would also reach the
        // navbar, the user menu toggler and the iframe tabs.

        if (config('adminlte.sidebar_nav_pills', false)) {
            $classes[] = Tokens::NAV_PILLS;
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
    public static function makeAttributes(): array
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
    protected static function isMiniEnabled(): bool
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
    protected static function isCollapsed(): bool
    {
        return (bool) config('adminlte.sidebar_collapse', false)
            || ! empty(View::getSection('sidebar_collapse'));
    }
}
