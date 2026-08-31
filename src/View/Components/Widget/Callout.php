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
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $theme = null, $icon = null, $title = null, $titleClass = null
    ) {
        $this->theme = $theme;
        $this->icon = $icon;
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->titleClass = $titleClass;
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
