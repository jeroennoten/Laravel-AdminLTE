<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class ProfileRowItem extends Component
{
    use HandlesThemeColors;

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
     * The item layout type. The 'default' type keeps the legacy markup (the
     * item is a 'div.col-{size}' holding a 'span.nav-link'), while the 'nav'
     * type emits the AdminLTE v4 reference markup (the item is a 'li.nav-item'
     * holding an 'a.nav-link', so it has to be placed inside an
     * 'ul.nav.flex-column' element).
     *
     * @var string
     */
    public $layoutType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $icon = null, $size = 12,
        $badge = null, $url = null, $urlTarget = 'title',
        $textTooltip = null, $layoutType = 'default'
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->textTooltip = UtilsHelper::applyHtmlEntityDecoder($textTooltip);
        $this->icon = $icon;
        $this->size = $size;
        $this->badge = $badge;
        $this->url = $url;
        $this->urlTarget = $urlTarget;

        // Setup the layout type.

        $this->layoutType = $layoutType;

        if (! in_array($this->layoutType, ['default', 'nav'])) {
            $this->layoutType = 'default';
        }
    }

    /**
     * Make the class attribute for the link of an item using the 'nav' layout
     * type. Note the AdminLTE v4 reference uses the Bootstrap 5 emphasis link
     * color, so the item is readable on both color modes.
     *
     * @return string
     */
    public function makeNavLinkClass()
    {
        $classes = ['nav-link'];

        if (! empty($this->url)) {
            $classes[] = 'link-body-emphasis';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the text wrapper class.
     *
     * @return string
     */
    public function makeTextWrapperClass()
    {
        $classes = ['float-end'];

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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.profile-row-item');
    }
}
