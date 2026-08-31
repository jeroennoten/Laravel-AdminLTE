<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper;

class Layout
{
    /**
     * Checks if the topnav layout is enabled.
     *
     * @return bool
     */
    public static function isTopnavEnabled()
    {
        return self::isEnabledBy('layout_topnav');
    }

    /**
     * Checks if the boxed layout is enabled. Note the boxed layout was removed
     * on AdminLTE v4, this is kept for backward compatibility only.
     *
     * @deprecated The boxed layout is not supported by AdminLTE v4.
     *
     * @return bool
     */
    public static function isBoxedEnabled()
    {
        return self::isEnabledBy('layout_boxed');
    }

    /**
     * Checks if the right sidebar is enabled.
     *
     * @return bool
     */
    public static function isRightSidebarEnabled()
    {
        return self::isEnabledBy('right_sidebar');
    }

    /**
     * Makes the set of classes of the content wrapper element.
     *
     * @return array
     */
    public static function makeContentWrapperClasses()
    {
        $classes = [Tokens::CONTENT_WRAPPER];
        $cfg = config('adminlte.classes_content_wrapper', '');

        if (is_string($cfg) && ! empty($cfg)) {
            $classes[] = $cfg;
        }

        // The preloader of the content wrapper is positioned over it.

        if (PreloaderHelper::isPreloaderEnabled('cwrapper')) {
            $classes[] = 'position-relative';
        }

        return $classes;
    }

    /**
     * Checks whether an option is enabled, either by the configuration file or
     * by the view section of the same name.
     *
     * @param  string  $option  The name of the option and of the section
     * @return bool
     */
    protected static function isEnabledBy($option)
    {
        return (bool) config("adminlte.{$option}", false)
            || ! empty(View::getSection($option));
    }
}
