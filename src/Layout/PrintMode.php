<?php

namespace JeroenNoten\LaravelAdminLte\Layout;

class PrintMode
{
    /**
     * The print tokens accepted by the AdminLTE stylesheet.
     *
     * The 'plain' token drops the printed link URLs and the outline around the
     * buttons. The 'app' token restores the printing of the layout chrome (the
     * header, the sidebar and the footer), which AdminLTE stopped printing by
     * default on its v4.9 release. Both are matched per token, so they can be
     * combined.
     *
     * @var array
     */
    protected static $tokens = ['plain', 'app'];

    /**
     * Gets the set of configured print tokens. Unknown tokens are dropped, so
     * the attribute never carries a value AdminLTE would not understand.
     *
     * @return array
     */
    public static function getTokens(): array
    {
        $cfg = config('adminlte.print', null);

        if (is_string($cfg)) {
            $cfg = preg_split('/\s+/', trim($cfg), -1, PREG_SPLIT_NO_EMPTY);
        }

        if (! is_array($cfg)) {
            return [];
        }

        $tokens = array_values(array_intersect(self::$tokens, array_map(
            static fn ($token) => is_string($token) ? $token : '',
            $cfg
        )));

        return $tokens;
    }

    /**
     * Makes the set of print attributes for the html tag.
     *
     * @return array
     */
    public static function makeHtmlAttributes(): array
    {
        $tokens = self::getTokens();

        if (empty($tokens)) {
            return [];
        }

        return [Tokens::PRINT_ATTRIBUTE.'="'.implode(' ', $tokens).'"'];
    }
}
