<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Callout extends Component
{
    use HandlesThemeColors;

    /**
     * The callout icon (a Bootstrap Icon).
     *
     * @var string
     */
    public $icon;

    /**
     * The callout theme (primary, secondary, info, success, warning, danger,
     * light or dark). Any color of the AdminLTE extended palette (navy, sky,
     * teal, ...) is also supported when the 'adminlte.assets.extended_colors'
     * option is enabled.
     *
     * @var string
     */
    public $theme;

    /**
     * The callout title.
     *
     * @var string
     */
    public $title;

    /**
     * Extra classes for the title container. This provides a way to customize
     * the title style.
     *
     * @var string
     */
    public $titleClass;

    /**
     * An url for the callout. When provided, a "callout-link" styled anchor
     * will be rendered at the end of the callout content, pointing to that
     * url. The AdminLTE v4 stylesheet paints it with the emphasis color of
     * the callout theme.
     *
     * @var string
     */
    public $url;

    /**
     * A text/label associated with the callout url. Defaults to the url
     * itself when not provided.
     *
     * @var string
     */
    public $urlText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $theme = null, $icon = null, $title = null, $titleClass = null,
        $url = null, $urlText = null
    ) {
        $this->theme = $theme;
        $this->icon = $icon;
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->titleClass = $titleClass;
        $this->url = $url;
        $this->urlText = UtilsHelper::applyHtmlEntityDecoder($urlText);
    }

    /**
     * Make the class attribute for the callout item.
     *
     * @return string
     */
    public function makeCalloutClass()
    {
        $classes = ['callout'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "callout-{$theme}";
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
        return view('adminlte::components.widget.callout');
    }
}
