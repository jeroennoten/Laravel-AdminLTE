<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use Illuminate\Support\Facades\View;

class Sidebar
{
    /**
     * The color mode declared on the sidebar element when the configuration
     * provides no valid one.
     *
     * @var string
     */
    public const DEFAULT_THEME = 'dark';

    /**
     * The speed, in milliseconds, of the treeview open and close animation.
     * It is the default of the AdminLTE treeview plugin as well.
     *
     * @var int
     */
    public const DEFAULT_NAV_ANIMATION_SPEED = 300;

    /**
     * Gets the name of the expand breakpoint of the sidebar. A configured
     * 'sidebar_breakpoint' width wins over the 'sidebar_expand' option when it
     * matches one of the widths AdminLTE ships, so both the media queries and
     * the push menu script agree on where the sidebar turns into an overlay.
     * A width AdminLTE has no stylesheet for is ignored, since honoring it
     * would leave them disagreeing.
     *
     * Note an unsupported 'sidebar_expand' value falls back to the default
     * breakpoint instead of emitting no expand class at all: without that
     * class the sidebar gets no column on the layout grid, and the push menu
     * plugin (which reads the breakpoint back from it) never finds one.
     *
     * @return string
     */
    protected static function expandBreakpoint(): string
    {
        $fromWidth = Tokens::sidebarBreakpointName(
            config('adminlte.sidebar_breakpoint')
        );

        if (isset($fromWidth)) {
            return $fromWidth;
        }

        $cfg = config('adminlte.sidebar_expand', Tokens::SIDEBAR_DEFAULT_EXPAND);
        $cfg = is_string($cfg) ? strtolower(trim($cfg)) : '';

        return in_array($cfg, Tokens::SIDEBAR_EXPAND_BREAKPOINTS, true)
            ? $cfg
            : Tokens::SIDEBAR_DEFAULT_EXPAND;
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

        $classes = [Tokens::sidebarExpand(self::expandBreakpoint())];

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
        $theme = self::theme();

        if (isset($theme)) {
            $attrs[] = Tokens::COLOR_MODE_ATTRIBUTE.'="'.$theme.'"';
        }

        // The AdminLTE push menu plugin remembers the collapsed state of the
        // sidebar between page loads through this attribute.

        if (config('adminlte.sidebar_collapse_remember', false)) {
            $attrs[] = Tokens::SIDEBAR_PERSISTENCE_ATTRIBUTE.'="true"';
        }

        // The plugin uses its own breakpoint (991.98 pixels, the 'lg' one) to
        // tell a desktop viewport from a mobile one, and it only reads the
        // width of the expand class back from the stylesheet while that media
        // query is already active. So, a layout that expands on another
        // breakpoint has to publish the width here: otherwise the script and
        // the media queries disagree on every viewport between both widths.
        // The width comes from the expand breakpoint actually in use, so they
        // can not be configured apart from each other.

        $expand = self::expandBreakpoint();

        if ($expand !== Tokens::SIDEBAR_DEFAULT_EXPAND) {
            $breakpoint = Tokens::sidebarBreakpointWidth($expand);

            if (isset($breakpoint)) {
                $attrs[] = Tokens::SIDEBAR_BREAKPOINT_ATTRIBUTE.'="'.$breakpoint.'"';
            }
        }

        return $attrs;
    }

    /**
     * Makes the set of data attributes of the sidebar navigation menu element.
     * They configure the AdminLTE treeview plugin, which reads them from the
     * element that holds its toggle attribute.
     *
     * @return array
     */
    public static function makeNavAttributes(): array
    {
        $attrs = [];
        $speed = self::navAnimationSpeed();

        // The plugin already defaults to this speed, so the attribute is only
        // emitted when another one is configured.

        if ($speed !== self::DEFAULT_NAV_ANIMATION_SPEED) {
            $attrs[] = 'data-animation-speed="'.$speed.'"';
        }

        // The plugin only accepts the exact 'false' string to opt out of the
        // accordion behavior, any other value keeps it enabled.

        if (! self::isNavAccordionEnabled()) {
            $attrs[] = 'data-accordion="false"';
        }

        return $attrs;
    }

    /**
     * Gets the speed, in milliseconds, of the treeview open and close
     * animation. Note the plugin parses the attribute with 'Number()', so a
     * value it can not parse would leave the treeview animating for 'NaN'
     * milliseconds. Such a value falls back to the default speed instead.
     *
     * @return int
     */
    public static function navAnimationSpeed(): int
    {
        $cfg = config(
            'adminlte.sidebar_nav_animation_speed', self::DEFAULT_NAV_ANIMATION_SPEED
        );

        if (! is_numeric($cfg)) {
            return self::DEFAULT_NAV_ANIMATION_SPEED;
        }

        return max(0, (int) $cfg);
    }

    /**
     * Checks whether only one treeview submenu can stay open at a time.
     *
     * @return bool
     */
    public static function isNavAccordionEnabled(): bool
    {
        return (bool) config('adminlte.sidebar_nav_accordion', true);
    }

    /**
     * Gets the color mode declared on the sidebar element. It returns null
     * when the sidebar inherits the color mode of the page. Note a value the
     * Bootstrap color modes do not provide falls back to the default theme,
     * otherwise a typo would silently turn the sidebar into an inherited one.
     *
     * @return string|null
     */
    protected static function theme(): ?string
    {
        $cfg = config('adminlte.sidebar_theme', self::DEFAULT_THEME);

        // Both null and false opt out of the attribute on purpose, so the
        // sidebar follows the color mode of the page.

        if ($cfg === null || $cfg === false || $cfg === '') {
            return null;
        }

        $cfg = is_string($cfg) ? strtolower(trim($cfg)) : '';

        return in_array($cfg, ['light', 'dark'], true) ? $cfg : self::DEFAULT_THEME;
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
