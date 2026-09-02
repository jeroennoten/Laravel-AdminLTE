<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Ribbon extends Component
{
    use HandlesThemeColors;

    /**
     * The available ribbon sizes.
     *
     * @var array
     */
    protected const SIZES = ['lg', 'xl'];

    /**
     * The ribbon label. It is replaced by the content of the default slot
     * whenever that slot is filled.
     *
     * @var string
     */
    public $label;

    /**
     * The ribbon theme (light, dark, primary, secondary, info, success,
     * warning or danger). Any color of the AdminLTE extended palette (navy,
     * sky, teal, ...) is also supported when the 'adminlte.assets
     * .extended_colors' option is enabled. Without a theme, the ribbon is
     * painted with the secondary background color of the active color mode.
     *
     * @var string
     */
    public $theme;

    /**
     * The ribbon size (lg or xl). The default size only fits about six
     * characters, the bigger sizes are meant for longer labels.
     *
     * @var string
     */
    public $size;

    /**
     * An URL for the ribbon. When defined, the ribbon label is wrapped inside
     * a link that inherits the contrast color of the theme.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null, $theme = null, $size = null, $url = null
    ) {
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->theme = $theme;
        $this->size = $size;
        $this->url = $url;
    }

    /**
     * Make the class attribute for the ribbon wrapper item.
     *
     * @return string
     */
    public function makeWrapperClass()
    {
        $classes = ['ribbon-wrapper'];

        if (isset($this->size) && in_array($this->size, static::SIZES)) {
            $classes[] = "ribbon-{$this->size}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the ribbon banner item.
     *
     * @return string
     */
    public function makeRibbonClass()
    {
        $classes = ['ribbon'];
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
        return view('adminlte::components.widget.ribbon');
    }
}
