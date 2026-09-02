<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Palette
{
    /**
     * The Bootstrap theme colors that the AdminLTE palette stylesheets accept
     * as a replacement of the primary color. The primary color itself is not
     * part of the set, since remapping it to itself is a no-op.
     *
     * @var array
     */
    protected static $bootstrapColors = [
        'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark',
    ];

    /**
     * Gets the color configured as a replacement of the primary one. It
     * returns null when no valid color is configured.
     *
     * @return string|null
     */
    public static function getPrimary()
    {
        $color = config('adminlte.assets.palette.primary', null);

        if (! is_string($color) || $color === '') {
            return null;
        }

        return in_array($color, self::getAvailableColors(), true) ? $color : null;
    }

    /**
     * Gets the contrast correction to apply on the palette. It returns null
     * when no correction applies.
     *
     * @return string|null
     */
    public static function getContrast()
    {
        $contrast = config('adminlte.assets.palette.contrast', null);

        // The v3 palette misses the WCAG AA contrast ratio on a set of its
        // colors, and the stylesheet provides a correction for exactly those.
        // So, the correction is applied by default on that palette.

        if ($contrast === null) {
            return self::isV3PaletteEnabled() ? 'aa' : null;
        }

        return $contrast === 'aa' ? 'aa' : null;
    }

    /**
     * Gets the set of colors accepted by the enabled palette stylesheet. The
     * palette attributes are only provided by those stylesheets, so the set is
     * empty when the extended colors are disabled.
     *
     * @return array
     */
    public static function getAvailableColors()
    {
        $extended = UtilsHelper::getExtendedColors();

        return empty($extended)
            ? []
            : array_merge(self::$bootstrapColors, $extended);
    }

    /**
     * Makes the set of palette attributes for the html tag.
     *
     * @return array
     */
    public static function makeHtmlAttributes()
    {
        $attrs = [];
        $primary = self::getPrimary();
        $contrast = self::getContrast();

        if (isset($primary)) {
            $attrs[] = Tokens::PALETTE_PRIMARY_ATTRIBUTE.'="'.$primary.'"';
        }

        if (isset($contrast)) {
            $attrs[] = Tokens::PALETTE_CONTRAST_ATTRIBUTE.'="'.$contrast.'"';
        }

        return $attrs;
    }

    /**
     * Checks whether the enabled palette is the one with the v3 color aliases.
     *
     * @return bool
     */
    protected static function isV3PaletteEnabled()
    {
        return ! empty(UtilsHelper::getExtendedColors())
            && (bool) config('adminlte.assets.extended_colors_v3_aliases', false);
    }
}
