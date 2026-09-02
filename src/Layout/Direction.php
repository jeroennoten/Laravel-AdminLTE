<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

class Direction
{
    /**
     * Checks if the RTL (right-to-left) mode is enabled. When the related
     * configuration is null, the mode is resolved from the current locale.
     *
     * @return bool
     */
    public static function isRtlEnabled(): bool
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
    public static function isRtlLocale($locale): bool
    {
        if (! is_string($locale)) {
            return false;
        }

        $locales = config('adminlte.rtl.locales', []);

        if (! is_array($locales)) {
            return false;
        }

        return self::matchesAnyLocale($locale, $locales);
    }

    /**
     * Gets the text direction of the admin panel ('rtl' or 'ltr').
     *
     * @return string
     */
    public static function get(): string
    {
        return self::isRtlEnabled() ? 'rtl' : 'ltr';
    }

    /**
     * Checks whether a locale matches any of the provided ones. Both the full
     * locale (for example 'uz-AF') and its language part (for example 'ar'
     * from 'ar_EG') are compared.
     *
     * @param  string  $locale  The locale to check
     * @param  array  $locales  The set of locales to compare with
     * @return bool
     */
    protected static function matchesAnyLocale($locale, $locales): bool
    {
        $locale = self::normalize($locale);
        $language = explode('-', $locale)[0];

        foreach ($locales as $candidate) {
            $candidate = self::normalize((string) $candidate);

            if (strcasecmp($candidate, $locale) === 0
                || strcasecmp($candidate, $language) === 0
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalizes a locale, so the underscore and the dash separators can be
     * compared with each other.
     *
     * @param  string  $locale  The locale to normalize
     * @return string
     */
    protected static function normalize($locale): string
    {
        return str_replace('_', '-', $locale);
    }
}
