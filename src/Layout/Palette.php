<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

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
     * The colors of the AdminLTE v4 extended palette, as provided by the
     * optional 'adminlte-colors.css' stylesheet.
     *
     * @var array
     */
    protected static $extendedColors = [
        'amber', 'fuchsia', 'graphite', 'indigo', 'midnight', 'navy', 'olive',
        'orange', 'pink', 'sky', 'slate', 'steel', 'teal', 'violet',
    ];

    /**
     * The colors of the AdminLTE v3 palette, as provided by the optional
     * 'adminlte-colors-v3.css' stylesheet.
     *
     * @var array
     */
    protected static $extendedColorsV3 = [
        'blue', 'cyan', 'fuchsia', 'gray', 'gray-dark', 'green', 'indigo',
        'lightblue', 'lime', 'maroon', 'navy', 'olive', 'orange', 'pink',
        'purple', 'red', 'teal', 'yellow',
    ];

    /**
     * The colors whose 'text-bg-*' utility paints a dark text, since their
     * background is light enough. Note the v3 names are part of the set, since
     * they are real colors when the v3 alias stylesheet is loaded.
     *
     * @var array
     */
    protected static $darkTextColors = [
        'info', 'warning', 'light', 'cyan', 'yellow',
    ];

    /**
     * The additional colors of the v3 palette that get a dark text once the
     * WCAG AA contrast correction is applied over that palette.
     *
     * @var array
     */
    protected static $darkTextColorsOnContrastAa = [
        'blue', 'fuchsia', 'green', 'lightblue', 'olive', 'pink', 'teal',
    ];

    /**
     * Gets the set of colors provided by the enabled extended palette. It
     * returns an empty array when the extended colors are disabled.
     *
     * @return array
     */
    public static function getExtendedColors(): array
    {
        if (! config('adminlte.assets.extended_colors', false)) {
            return [];
        }

        return config('adminlte.assets.extended_colors_v3_aliases', false)
            ? static::$extendedColorsV3
            : static::$extendedColors;
    }

    /**
     * Checks whether a color paints a dark text over its own background. It's
     * the predicate behind the contrast of any element placed over a themed
     * background (links, close buttons, ...).
     *
     * @param  string|null  $color  The theme color name
     * @return bool
     */
    public static function hasDarkText($color): bool
    {
        if (empty($color) || ! is_string($color)) {
            return false;
        }

        if (in_array($color, static::$darkTextColors, true)) {
            return true;
        }

        return self::getContrast() === 'aa'
            && in_array($color, static::$darkTextColorsOnContrastAa, true);
    }

    /**
     * Gets the color configured as a replacement of the primary one. It
     * returns null when no valid color is configured.
     *
     * @return string|null
     */
    public static function getPrimary(): ?string
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
    public static function getContrast(): ?string
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
    public static function getAvailableColors(): array
    {
        $extended = self::getExtendedColors();

        return empty($extended)
            ? []
            : array_merge(static::$bootstrapColors, $extended);
    }

    /**
     * Makes the set of palette attributes for the html tag.
     *
     * @return array
     */
    public static function makeHtmlAttributes(): array
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
    protected static function isV3PaletteEnabled(): bool
    {
        return ! empty(self::getExtendedColors())
            && (bool) config('adminlte.assets.extended_colors_v3_aliases', false);
    }
}
