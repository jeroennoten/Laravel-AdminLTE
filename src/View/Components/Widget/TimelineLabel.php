<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class TimelineLabel extends Component
{
    use HandlesThemeColors;

    /**
     * The text for the timeline label. When not defined, the content of the
     * default slot is used instead.
     *
     * @var string
     */
    public $label;

    /**
     * The label theme (light, dark, primary, secondary, info, success, warning
     * or danger). Any color of the AdminLTE extended palette (navy, sky, teal,
     * ...) is also supported when the 'adminlte.assets.extended_colors' option
     * is enabled. The AdminLTE v3 color names (lightblue, maroon, ...) are
     * still accepted and translated to their v4 equivalent.
     *
     * @var string
     */
    public $theme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = null, $theme = null)
    {
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->theme = $theme;
    }

    /**
     * Make the class attribute for the label badge.
     *
     * @return string
     */
    public function makeLabelClass()
    {
        $classes = [];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
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
        return view('adminlte::components.widget.timeline-label');
    }
}
