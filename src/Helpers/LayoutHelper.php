<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

use JeroenNoten\LaravelAdminLte\Layout\BodyClasses;
use JeroenNoten\LaravelAdminLte\Layout\ColorMode;
use JeroenNoten\LaravelAdminLte\Layout\Direction;
use JeroenNoten\LaravelAdminLte\Layout\Layout;
use JeroenNoten\LaravelAdminLte\Layout\Palette;
use JeroenNoten\LaravelAdminLte\Layout\Sidebar;

class LayoutHelper
{
    /**
     * Checks if layout topnav is enabled.
     *
     * @return bool
     */
    public static function isLayoutTopnavEnabled()
    {
        return Layout::isTopnavEnabled();
    }

    /**
     * Checks if layout boxed is enabled. Note the boxed layout was removed on
     * AdminLTE v4, this method is kept for backward compatibility only.
     *
     * @deprecated The boxed layout is not supported by AdminLTE v4. It will
     * be removed on the 5.0 release.
     *
     * @return bool
     */
    public static function isLayoutBoxedEnabled()
    {
        return Layout::isBoxedEnabled();
    }

    /**
     * Checks if right sidebar is enabled.
     *
     * @return bool
     */
    public static function isRightSidebarEnabled()
    {
        return Layout::isRightSidebarEnabled();
    }

    /**
     * Checks if the RTL (right-to-left) mode is enabled. When the related
     * configuration is null, the mode is resolved from the current locale.
     *
     * @return bool
     */
    public static function isRtlEnabled()
    {
        return Direction::isRtlEnabled();
    }

    /**
     * Checks whether the specified locale is a right-to-left one.
     *
     * @param  string  $locale  The locale to check (for example: 'ar')
     * @return bool
     */
    public static function isRtlLocale($locale)
    {
        return Direction::isRtlLocale($locale);
    }

    /**
     * Gets the text direction of the admin panel ('rtl' or 'ltr').
     *
     * @return string
     */
    public static function getHtmlDirection()
    {
        return Direction::get();
    }

    /**
     * Gets the configured (initial) color mode of the admin panel. It returns
     * one of the next tokens: 'light', 'dark' or 'auto'.
     *
     * @return string
     */
    public static function getColorMode()
    {
        return ColorMode::get();
    }

    /**
     * Checks if dark mode is currently active (server side preference).
     *
     * @return bool
     */
    public static function isDarkModeEnabled()
    {
        return ColorMode::isDarkModeEnabled();
    }

    /**
     * Checks whether the navbar (app-header) is configured as fixed.
     *
     * @return bool
     */
    public static function isFixedNavbarEnabled()
    {
        return BodyClasses::isFixedNavbarEnabled();
    }

    /**
     * Checks whether the footer is configured as fixed.
     *
     * @return bool
     */
    public static function isFixedFooterEnabled()
    {
        return BodyClasses::isFixedFooterEnabled();
    }

    /**
     * Makes and return the set of attributes related to the html tag.
     *
     * @return string
     */
    public static function makeHtmlData()
    {
        $attrs = [];

        if (Direction::isRtlEnabled()) {
            $attrs[] = 'dir="rtl"';
        }

        return self::join(array_merge(
            $attrs,
            ColorMode::makeHtmlAttributes(),
            Palette::makeHtmlAttributes()
        ));
    }

    /**
     * Makes and return the set of classes related to the body tag.
     *
     * @return string
     */
    public static function makeBodyClasses()
    {
        return self::join(BodyClasses::make());
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
     * @deprecated Use the {@see makeHtmlData()} method instead. It will be
     * removed on the 5.0 release.
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
        return self::join(Sidebar::makeClasses());
    }

    /**
     * Makes and return the set of classes related to the main sidebar
     * navigation menu element.
     *
     * @return string
     */
    public static function makeSidebarNavClasses()
    {
        return self::join(Sidebar::makeNavClasses());
    }

    /**
     * Makes and return the data attributes of the main sidebar element.
     *
     * @return string
     */
    public static function makeSidebarData()
    {
        return self::join(Sidebar::makeAttributes());
    }

    /**
     * Makes and return the set of classes related to the wrapper element
     * (app-wrapper in AdminLTE v4).
     *
     * @return string
     */
    public static function makeWrapperClasses()
    {
        return self::join(Layout::wrapperClasses());
    }

    /**
     * Makes and return the set of classes related to the content wrapper
     * element (app-main in AdminLTE v4).
     *
     * @return string
     */
    public static function makeContentWrapperClasses()
    {
        return self::join(Layout::contentWrapperClasses());
    }

    /**
     * Joins a set of classes or attributes into a single string.
     *
     * @param  array  $tokens  The set of tokens to join
     * @return string
     */
    protected static function join($tokens): string
    {
        return trim(implode(' ', $tokens));
    }
}
