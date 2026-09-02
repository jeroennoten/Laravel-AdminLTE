<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;

class Timeline extends Component
{
    use HandlesThemeColors;

    /**
     * Indicates if the timeline uses the inverse style. On the inverse style
     * the items drop their shadow and get a plain border instead.
     *
     * @var bool|mixed
     */
    public $inverse;

    /**
     * A Bootstrap Icon for the element that closes the timeline. When defined,
     * an extra entry holding only that icon is appended at the end of the
     * timeline, as done by the AdminLTE reference markup.
     *
     * @var string
     */
    public $endIcon;

    /**
     * The theme for the closing icon (light, dark, primary, secondary, info,
     * success, warning or danger). Any color of the AdminLTE extended palette
     * (navy, sky, teal, ...) is also supported when the
     * 'adminlte.assets.extended_colors' option is enabled. The AdminLTE v3
     * color names (lightblue, maroon, ...) are still accepted and translated
     * to their v4 equivalent.
     *
     * @var string
     */
    public $endIconTheme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $inverse = null, $endIcon = null, $endIconTheme = null
    ) {
        $this->inverse = $inverse;
        $this->endIcon = $endIcon;
        $this->endIconTheme = $endIconTheme;
    }

    /**
     * Make the class attribute for the timeline container.
     *
     * @return string
     */
    public function makeTimelineClass()
    {
        $classes = ['timeline'];

        if ($this->inverse) {
            $classes[] = 'timeline-inverse';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the icon that closes the timeline.
     *
     * @return string
     */
    public function makeEndIconClass()
    {
        $classes = ['timeline-icon'];

        if (! empty($this->endIcon)) {
            $classes[] = $this->endIcon;
        }

        $theme = $this->resolveThemeColor($this->endIconTheme);

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
        return view('adminlte::components.widget.timeline');
    }
}
