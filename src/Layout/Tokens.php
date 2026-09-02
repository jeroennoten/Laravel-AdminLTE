<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

/**
 * Holds the vocabulary of the underlying AdminLTE distribution.
 *
 * Every class name and every token that AdminLTE defines is declared here, so
 * an update of the template only requires changing this file instead of the
 * views and the helpers that consume them.
 */
class Tokens
{
    /**
     * The class of the element that wraps the whole layout.
     *
     * @var string
     */
    public const WRAPPER = 'app-wrapper';

    /**
     * The class of the main sidebar element.
     *
     * @var string
     */
    public const SIDEBAR = 'app-sidebar';

    /**
     * The class of the content wrapper element.
     *
     * @var string
     */
    public const CONTENT_WRAPPER = 'app-main';

    /**
     * The class that fixes the sidebar to the viewport.
     *
     * @var string
     */
    public const LAYOUT_FIXED = 'layout-fixed';

    /**
     * The class that fixes the navbar to the viewport.
     *
     * @var string
     */
    public const FIXED_NAVBAR = 'fixed-header';

    /**
     * The class that fixes the footer to the viewport.
     *
     * @var string
     */
    public const FIXED_FOOTER = 'fixed-footer';

    /**
     * The prefix of the classes that define the sidebar expand breakpoint.
     *
     * @var string
     */
    public const SIDEBAR_EXPAND_PREFIX = 'sidebar-expand-';

    /**
     * The class that enables the collapsed mini sidebar mode.
     *
     * @var string
     */
    public const SIDEBAR_MINI = 'sidebar-mini';

    /**
     * The class that starts the sidebar on its collapsed state.
     *
     * @var string
     */
    public const SIDEBAR_COLLAPSE = 'sidebar-collapse';

    /**
     * The class that keeps a collapsed sidebar collapsed on mouse hover.
     *
     * @var string
     */
    public const SIDEBAR_WITHOUT_HOVER = 'sidebar-without-hover';

    /**
     * The attribute that holds the color mode of an element.
     *
     * @var string
     */
    public const COLOR_MODE_ATTRIBUTE = 'data-bs-theme';

    /**
     * The attribute that disables the AdminLTE color mode plugin.
     *
     * @var string
     */
    public const COLOR_MODE_DISABLED_ATTRIBUTE = 'data-lte-color-mode';

    /**
     * The attribute that enables the persistence of the sidebar state.
     *
     * @var string
     */
    public const SIDEBAR_PERSISTENCE_ATTRIBUTE = 'data-enable-persistence';

    /**
     * The breakpoints supported by the sidebar expand classes.
     *
     * @var array
     */
    public const SIDEBAR_EXPAND_BREAKPOINTS = ['sm', 'md', 'lg', 'xl', 'xxl'];

    /**
     * The supported color modes.
     *
     * @var array
     */
    public const COLOR_MODES = ['light', 'dark', 'auto'];

    /**
     * The class that reduces the dimensions of the layout elements.
     *
     * @var string
     */
    public const COMPACT_MODE = 'compact-mode';

    /**
     * The attribute that remaps the primary color of the palette.
     *
     * @var string
     */
    public const PALETTE_PRIMARY_ATTRIBUTE = 'data-lte-primary';

    /**
     * The attribute that applies the contrast correction of the palette.
     *
     * @var string
     */
    public const PALETTE_CONTRAST_ATTRIBUTE = 'data-lte-contrast';

    /**
     * The breakpoint tokens accepted by the legacy 'sidebar_mini' option.
     *
     * @var array
     */
    public const LEGACY_SIDEBAR_MINI_TOKENS = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];

    /**
     * Makes the class that expands the sidebar on the given breakpoint.
     *
     * @param  string  $breakpoint  One of the supported breakpoints
     * @return string|null
     */
    public static function sidebarExpand($breakpoint)
    {
        if (! in_array($breakpoint, self::SIDEBAR_EXPAND_BREAKPOINTS, true)) {
            return null;
        }

        return self::SIDEBAR_EXPAND_PREFIX.$breakpoint;
    }
}
