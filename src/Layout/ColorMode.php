<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use JeroenNoten\LaravelAdminLte\Http\Controllers\DarkModeController;

class ColorMode
{
    /**
     * Gets the configured (initial) color mode of the admin panel. It returns
     * one of the next tokens: 'light', 'dark' or 'auto'.
     *
     * @return string
     */
    public static function get(): string
    {
        $legacy = self::fromLegacyOptions();

        if (isset($legacy)) {
            return $legacy;
        }

        $mode = config('adminlte.color_mode.default', 'auto');

        return in_array($mode, Tokens::COLOR_MODES, true) ? $mode : 'auto';
    }

    /**
     * Checks if dark mode is currently active (server side preference).
     *
     * @return bool
     */
    public static function isDarkModeEnabled(): bool
    {
        $darkModeCtrl = new DarkModeController();
        event(new ReadingDarkModePreference($darkModeCtrl));

        return $darkModeCtrl->isEnabled();
    }

    /**
     * Checks whether the visitor choice is persisted on the browser by the
     * AdminLTE color mode plugin.
     *
     * @return bool
     */
    public static function isRemembered(): bool
    {
        return (bool) config('adminlte.color_mode.remember', true);
    }

    /**
     * Checks whether the AdminLTE color mode plugin is enabled.
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return config('adminlte.color_mode.enabled', true) !== false;
    }

    /**
     * Makes the set of attributes that declare the color mode on the html tag.
     *
     * @return array
     */
    public static function makeHtmlAttributes(): array
    {
        $attrs = [];
        $mode = self::get();

        // The color mode plugin is opted out completely, so the application
        // takes over the theming of the template.

        if (! self::isEnabled()) {
            return [
                Tokens::COLOR_MODE_ATTRIBUTE.'="'.($mode === 'auto' ? 'light' : $mode).'"',
                Tokens::COLOR_MODE_DISABLED_ATTRIBUTE.'="off"',
            ];
        }

        // The automatic mode is resolved on the client side, so it declares no
        // color mode at all.

        if ($mode !== 'auto') {
            $attrs[] = Tokens::COLOR_MODE_ATTRIBUTE.'="'.$mode.'"';
        }

        // Without the client side persistence the package provides its own
        // toggle, so the AdminLTE plugin is disabled in order to not restore
        // its stored value. The automatic mode is the exception, since it can
        // only be resolved by that plugin.

        if ($mode !== 'auto' && ! self::isRemembered()) {
            $attrs[] = Tokens::COLOR_MODE_DISABLED_ATTRIBUTE.'="off"';
        }

        return $attrs;
    }

    /**
     * Resolves the color mode from the options of the previous package
     * releases. It returns null when none of them applies.
     *
     * @return string|null
     */
    protected static function fromLegacyOptions(): ?string
    {
        $legacy = config('adminlte.layout_theme_mode', null);

        if (in_array($legacy, Tokens::COLOR_MODES, true)) {
            return $legacy;
        }

        if (config('adminlte.layout_dark_mode', false) === true) {
            return 'dark';
        }

        return self::isDarkModeEnabled() ? 'dark' : null;
    }
}
