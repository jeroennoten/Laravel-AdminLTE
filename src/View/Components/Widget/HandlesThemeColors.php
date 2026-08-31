<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

trait HandlesThemeColors
{
    /**
     * The map of AdminLTE v3 color names that were renamed or dropped on the
     * AdminLTE v4 extended palette (dist/css/adminlte-colors.css). The old
     * names are still accepted on every component and translated on the fly,
     * unless the v3 alias stylesheet (dist/css/adminlte-colors-v3.css) is in
     * use, in that case the old names are valid CSS classes on their own.
     *
     * @var array
     */
    protected static $v3ColorAliases = [
        'lightblue' => 'sky',
        'maroon' => 'pink',
        'purple' => 'violet',
        'lime' => 'olive',
        'blue' => 'primary',
        'red' => 'danger',
        'green' => 'success',
        'yellow' => 'warning',
        'cyan' => 'info',
        'gray' => 'secondary',
        'gray-dark' => 'dark',
    ];

    /**
     * Resolve a theme color name into the name used by AdminLTE v4. The
     * Bootstrap theme colors (primary, secondary, success, danger, warning,
     * info, light and dark) are always available, the extended palette
     * requires the 'adminlte.assets.extended_colors' option to be enabled.
     *
     * @param  string|null  $theme  The theme color name
     * @return string|null
     */
    protected function resolveThemeColor($theme)
    {
        if (empty($theme) || ! is_string($theme)) {
            return $theme;
        }

        // When the v3 alias stylesheet is loaded, the old AdminLTE v3 color
        // names exist as real CSS classes, so, keep them untouched.

        if (config('adminlte.assets.extended_colors_v3_aliases', false)) {
            return $theme;
        }

        return self::$v3ColorAliases[$theme] ?? $theme;
    }
}
