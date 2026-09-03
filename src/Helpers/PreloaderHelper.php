<?php

namespace JeroenNoten\LaravelAdminLte\Helpers;

class PreloaderHelper
{
    /**
     * The preloader modes supported by the package. The 'fullscreen' one
     * covers the whole viewport, the 'cwrapper' one is attached to the
     * content wrapper and thus leaves the sidebars and the navbar visible.
     *
     * @var array
     */
    public const MODES = ['fullscreen', 'cwrapper'];

    /**
     * The mode used when the configured one is not supported.
     *
     * @var string
     */
    public const DEFAULT_MODE = 'fullscreen';

    /**
     * Check if the preloader animation is enabled for the specified mode.
     *
     * @param  string  $mode  The preloader mode to check.
     * @return bool
     */
    public static function isPreloaderEnabled($mode = self::DEFAULT_MODE)
    {
        return (bool) config('adminlte.preloader.enabled', false)
            && self::getMode() === $mode;
    }

    /**
     * Gets the configured preloader mode. An unsupported value falls back to
     * the default mode: it would otherwise disable the preloader altogether,
     * since no rendering branch would ever match it.
     *
     * @return string
     */
    public static function getMode()
    {
        $mode = config('adminlte.preloader.mode', self::DEFAULT_MODE);
        $mode = is_string($mode) ? strtolower(trim($mode)) : '';

        return in_array($mode, self::MODES, true) ? $mode : self::DEFAULT_MODE;
    }

    /**
     * Make and return the set of classes related to the preloader animation.
     *
     * @return string
     */
    public static function makePreloaderClasses()
    {
        // Setup the base set of classes for the preloader.

        $classes = [
            'preloader',
            'flex-column',
            'justify-content-center',
            'align-items-center',
        ];

        // When the preloader is attached to the content-wrapper, the CSS
        // position attribute should be absolute.

        if (self::isPreloaderEnabled('cwrapper')) {
            $classes[] = 'position-absolute';
        }

        return trim(implode(' ', $classes));
    }

    /**
     * Make and return the set of styles related to the preloader.
     *
     * @return string
     */
    public static function makePreloaderStyle()
    {
        $styles = [];

        // When the preloader is attached to the content-wrapper, the CSS
        // z-index attribute should be less than the value of z-index for the
        // sidebars, the top navbar and the footer (they all are between 1030
        // and 1040). This way, we avoid overlapping with those elements.

        if (self::isPreloaderEnabled('cwrapper')) {
            $styles[] = 'z-index:1000';
        }

        return trim(implode(';', $styles));
    }
}
