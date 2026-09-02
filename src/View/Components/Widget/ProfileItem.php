<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

abstract class ProfileItem extends Component
{
    use HandlesThemeColors;

    /**
     * The base set of classes for the text wrapper of the item. It provides a
     * way for the concrete items to setup their own layout.
     *
     * @var array
     */
    protected $textWrapperBaseClass = [];

    /**
     * The title/header for the item.
     *
     * @var string
     */
    public $title;

    /**
     * The text/description for the item.
     *
     * @var string
     */
    public $text;

    /**
     * An extra tooltip for the text of the item.
     *
     * @var string
     */
    public $textTooltip;

    /**
     * A Bootstrap Icon for the item.
     *
     * @var string
     */
    public $icon;

    /**
     * The item size. Used to wrap the item inside a col-size div.
     *
     * @var int
     */
    public $size;

    /**
     * The badge theme for the text attribute. When used, the text attribute
     * will be wrapped inside a badge of the configured theme. Available themes
     * are: light, dark, primary, secondary, info, success, warning, danger or
     * any color of the AdminLTE extended palette like sky or teal. You can
     * also prepend the 'pill-' token for a pill badge, e.g: 'pill-info'.
     *
     * @var string
     */
    public $badge;

    /**
     * Setup an url for the item. When enabled the title attribute will be
     * wrapped inside a link pointing to that url.
     *
     * @var string
     */
    public $url;

    /**
     * The target element for the URL (title or text).
     *
     * @var string
     */
    public $urlTarget;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $icon = null, $size = null,
        $badge = null, $url = null, $urlTarget = 'title',
        $textTooltip = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->textTooltip = UtilsHelper::applyHtmlEntityDecoder($textTooltip);
        $this->icon = $icon;
        $this->size = $size;
        $this->badge = $badge;
        $this->url = $url;
        $this->urlTarget = $urlTarget;
    }

    /**
     * Make the text wrapper class.
     *
     * @return string
     */
    public function makeTextWrapperClass()
    {
        $classes = $this->textWrapperBaseClass;

        if (isset($this->badge)) {
            $isPill = str_starts_with($this->badge, 'pill-');
            $badgeTheme = $this->resolveThemeColor(
                str_replace('pill-', '', $this->badge)
            );

            $classes[] = 'badge';
            $classes[] = "text-bg-{$badgeTheme}";

            if ($isPill) {
                $classes[] = 'rounded-pill';
            }
        }

        return implode(' ', $classes);
    }
}
