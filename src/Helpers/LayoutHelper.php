<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController;

class LayoutHelper
{
    /**
     * Holds the set of tokens related to the sidebar expand breakpoints that
     * are supported by AdminLTE v4.
     *
     * @var array
     */
    protected static $sidebarExpandValues = ['sm', 'md', 'lg', 'xl', 'xxl'];

    /**
     * Holds the set of supported color modes.
     *
     * @var array
     */
    protected static $colorModes = ['light', 'dark', 'auto'];

    /**
     * Checks if layout topnav is enabled.
     *
     * @return bool
     */
    public static function isLayoutTopnavEnabled()
    {
        return config('adminlte.layout_topnav', false)
            || ! empty(View::getSection('layout_topnav'));
    }

    /**
     * Checks if layout boxed is enabled. Note the boxed layout was removed on
     * AdminLTE v4, this method is kept for backward compatibility only.
     *
     * @deprecated The boxed layout is not supported by AdminLTE v4.
     *
     * @return bool
     */
    public static function isLayoutBoxedEnabled()
    {
        return config('adminlte.layout_boxed', false)
            || ! empty(View::getSection('layout_boxed'));
    }

    /**
     * Checks if right sidebar is enabled.
     *
     * @return bool
     */
    public static function isRightSidebarEnabled()
    {
        return config('adminlte.right_sidebar', false)
            || ! empty(View::getSection('right_sidebar'));
    }

    /**
     * Checks if the RTL (right-to-left) mode is enabled. When the related
     * configuration is null, the mode is resolved from the current locale.
     *
     * @return bool
     */
    public static function isRtlEnabled()
    {
        $cfg = config('adminlte.rtl.enabled', null);

        if (is_bool($cfg)) {
            return $cfg;
        }

        return self::isRtlLocale(app()->getLocale());
    }

    /**
     * Checks whether the specified locale is a right-to-left one.
     *
     * @param  string  $locale  The locale to check (for example: 'ar')
     * @return bool
     */
    public static function isRtlLocale($locale)
    {
        $locales = config('adminlte.rtl.locales', []);

        if (! is_array($locales) || ! is_string($locale)) {
            return false;
        }

        // Normalize the locale and compare it with the configured ones. Both
        // the full locale (for example 'uz-AF') and its language part (for
        // example 'ar' from 'ar_EG') are checked.

        $locale = str_replace('_', '-', $locale);
        $language = explode('-', $locale)[0];

        foreach ($locales as $rtlLocale) {
            $rtlLocale = str_replace('_', '-', (string) $rtlLocale);

            if (strcasecmp($rtlLocale, $locale) === 0
                || strcasecmp($rtlLocale, $language) === 0
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the text direction of the admin panel ('rtl' or 'ltr').
     *
     * @return string
     */
    public static function getHtmlDirection()
    {
        return self::isRtlEnabled() ? 'rtl' : 'ltr';
    }

    /**
     * Gets the configured (initial) color mode of the admin panel. It returns
     * one of the next tokens: 'light', 'dark' or 'auto'.
     *
     * @return string
     */
    public static function getColorMode()
    {
        // The legacy 'layout_theme_mode' and 'layout_dark_mode' options are
        // still supported for backward compatibility.

        $legacy = config('adminlte.layout_theme_mode', null);

        if (in_array($legacy, self::$colorModes)) {
            return $legacy;
        }

        if (config('adminlte.layout_dark_mode', false) === true) {
            return 'dark';
        }

        // Check for a dark mode preference resolved on the server side.

        if (self::isDarkModeEnabled()) {
            return 'dark';
        }

        $mode = config('adminlte.color_mode.default', 'auto');

        return in_array($mode, self::$colorModes) ? $mode : 'auto';
    }

    /**
     * Checks if dark mode is currently active (server side preference).
     *
     * @return bool
     */
    public static function isDarkModeEnabled()
    {
        $darkModeCtrl = new DarkModeController();
        event(new ReadingDarkModePreference($darkModeCtrl));

        return $darkModeCtrl->isEnabled();
    }

    /**
     * Makes and return the set of attributes related to the html tag.
     *
     * @return string
     */
    public static function makeHtmlData()
    {
        $attrs = [];

        // Add the text direction attribute when the RTL mode is enabled.

        if (self::isRtlEnabled()) {
            $attrs[] = 'dir="rtl"';
        }

        // Add the Bootstrap 5.3 color mode attribute. Note the 'auto' mode is
        // resolved on the client side by the AdminLTE color mode widget.

        $colorMode = self::getColorMode();

        if ($colorMode !== 'auto') {
            $attrs[] = "data-bs-theme=\"{$colorMode}\"";
        }

        // When the color mode is not remembered on the browser, the package
        // provides its own (server side) toggle. So, the AdminLTE color mode
        // plugin is disabled, otherwise it would restore its stored value and
        // fight with the preference resolved on the server.
        //
        // Note the automatic mode is the exception: it has to be resolved on
        // the client side from the operating system preference, so the plugin
        // (and the no flash script) must stay enabled for it.

        if ($colorMode !== 'auto' && ! config('adminlte.color_mode.remember', true)) {
            $attrs[] = 'data-lte-color-mode="off"';
        }

        return trim(implode(' ', $attrs));
    }

    /**
     * Makes and return the set of classes related to the body tag.
     *
     * @return string
     */
    public static function makeBodyClasses()
    {
        $classes = [];
        array_push($classes, ...self::makeLayoutClasses());
        array_push($classes, ...self::makeSidebarClasses());
        array_push($classes, ...self::makeCustomBodyClasses());

        return trim(implode(' ', array_unique($classes)));
    }

    /**
     * Makes and return the set of data attributes related to the body tag.
     *
     * @return string
     */
    public static function makeBodyData()
    {
        return '';
    }

    /**
     * Makes and return the data attributes for the app-wrapper element. Note
     * that on AdminLTE v4 the color mode is applied on the html element, so
     * this method is kept for backward compatibility only.
     *
     * @deprecated Use the {@see makeHtmlData()} method instead.
     *
     * @return string
     */
    public static function makeWrapperData()
    {
        return '';
    }

    /**
     * Makes and return the set of classes related to the main sidebar element.
     *
     * @return string
     */
    public static function makeSidebarWrapperClasses()
    {
        $classes = ['app-sidebar'];
        $cfg = config('adminlte.classes_sidebar', 'bg-body-secondary shadow');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        return trim(implode(' ', $classes));
    }

    /**
     * Makes and return the data attributes of the main sidebar element.
     *
     * @return string
     */
    public static function makeSidebarData()
    {
        $attrs = [];

        // Add the color mode of the sidebar.

        $theme = config('adminlte.sidebar_theme', 'dark');

        if (in_array($theme, ['light', 'dark'])) {
            $attrs[] = "data-bs-theme=\"{$theme}\"";
        }

        // Add the attribute that makes the AdminLTE v4 PushMenu plugin to
        // remember the collapsed state of the sidebar between page loads.

        if (config('adminlte.sidebar_collapse_remember', false)) {
            $attrs[] = 'data-enable-persistence="true"';
        }

        return trim(implode(' ', $attrs));
    }

    /**
     * Makes and return the set of classes related to the content wrapper
     * element (app-main in AdminLTE v4).
     *
     * @return string
     */
    public static function makeContentWrapperClasses()
    {
        $classes = ['app-main'];

        // Add classes from the configuration file.

        $cfg = config('adminlte.classes_content_wrapper', '');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        // Add position-relative when using a content-wrapper preloader.

        if (PreloaderHelper::isPreloaderEnabled('cwrapper')) {
            $classes[] = 'position-relative';
        }

        return trim(implode(' ', $classes));
    }

    /**
     * Makes and return the set of classes related to the layout configuration.
     *
     * @return array
     */
    private static function makeLayoutClasses()
    {
        $classes = [];

        // Add classes related to the fixed sidebar layout configuration. The
        // fixed sidebar is not compatible with the topnav layout.

        $fixedSidebar = config('adminlte.layout_fixed_sidebar', false);

        if (! self::isLayoutTopnavEnabled() && $fixedSidebar) {
            $classes[] = 'layout-fixed';
        }

        // Add classes related to the fixed navbar/footer configuration.

        if (self::isFixedSectionEnabled('navbar')) {
            $classes[] = 'fixed-header';
        }

        if (self::isFixedSectionEnabled('footer')) {
            $classes[] = 'fixed-footer';
        }

        return $classes;
    }

    /**
     * Checks whether the navbar (app-header) is configured as fixed.
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
     * Checks whether the fixed mode is enabled for the specified layout
     * section. Note AdminLTE v4 does not support responsive fixed modes, so
     * an array configuration is considered enabled when any of its values is
     * enabled.
     *
     * @param  string  $section  The layout section (navbar or footer)
     * @return bool
     */
    private static function isFixedSectionEnabled($section)
    {
        $cfg = config("adminlte.layout_fixed_{$section}", false);

        if (is_array($cfg)) {
            return in_array(true, $cfg, true);
        }

        return (bool) $cfg;
    }

    /**
     * Makes the set of classes related to the main left sidebar configuration.
     *
     * @return array
     */
    private static function makeSidebarClasses()
    {
        $classes = [];

        // The topnav layout has no sidebar, so no sidebar classes are needed.

        if (self::isLayoutTopnavEnabled()) {
            return $classes;
        }

        // Add the class related to the "sidebar_expand" configuration. It
        // defines the breakpoint where the sidebar switches to the offcanvas
        // (mobile) behavior.

        $expand = config('adminlte.sidebar_expand', 'lg');

        if (in_array($expand, self::$sidebarExpandValues)) {
            $classes[] = "sidebar-expand-{$expand}";
        }

        // Add classes related to the "sidebar_mini" configuration. Note the
        // legacy 'xs', 'md' and 'lg' values are still supported.

        if (self::isSidebarMiniEnabled()) {
            $classes[] = 'sidebar-mini';
        }

        // Add classes related to the "sidebar_collapse" configuration.

        $sidebarCollapse = config('adminlte.sidebar_collapse', false)
            || ! empty(View::getSection('sidebar_collapse'));

        if ($sidebarCollapse) {
            $classes[] = 'sidebar-collapse';
        }

        // Add classes related to the "sidebar_without_hover" configuration.

        if (config('adminlte.sidebar_without_hover', false)) {
            $classes[] = 'sidebar-without-hover';
        }

        return $classes;
    }

    /**
     * Checks whether the sidebar mini mode is enabled.
     *
     * @return bool
     */
    private static function isSidebarMiniEnabled()
    {
        $cfg = config('adminlte.sidebar_mini', true);

        if (is_string($cfg)) {
            return in_array($cfg, ['xs', 'sm', 'md', 'lg', 'xl', 'xxl']);
        }

        return (bool) $cfg;
    }

    /**
     * Makes the set of classes related to custom body classes configuration.
     *
     * @return array
     */
    private static function makeCustomBodyClasses()
    {
        $classes = [];
        $cfg = config('adminlte.classes_body', '');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        return $classes;
    }
}
