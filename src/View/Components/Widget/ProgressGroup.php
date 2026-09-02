<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class ProgressGroup extends Component
{
    use HandlesThemeColors;

    /**
     * The label/description of the progress group.
     *
     * @var string
     */
    public $label;

    /**
     * The current value of the progress group. Together with the maximum
     * value, it defines the percentage of the underlying progress bar.
     *
     * @var mixed
     */
    public $value;

    /**
     * The maximum value of the progress group.
     *
     * @var mixed
     */
    public $max;

    /**
     * The progress bar theme (light, dark, primary, secondary, info, success,
     * warning, danger or any color of the AdminLTE extended palette like sky
     * or teal). Set to an empty value to inherit the color of the container.
     *
     * @var string
     */
    public $theme;

    /**
     * The progress bar size (sm, xs or xxs).
     *
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null, $value = 0, $max = 100, $theme = 'primary', $size = 'sm'
    ) {
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->value = $value;
        $this->max = $max;
        $this->theme = $theme;
        $this->size = $size;
    }

    /**
     * Make the percentage (an integer between 0 and 100) of the underlying
     * progress bar. A non positive maximum value gives an empty bar.
     *
     * @return int
     */
    public function makePercentage()
    {
        $max = (float) $this->max;

        if ($max <= 0) {
            return 0;
        }

        $ratio = max(min((float) $this->value / $max, 1), 0);

        return (int) round($ratio * 100);
    }

    /**
     * Make the theme for the underlying progress bar.
     *
     * @return string
     */
    public function makeBarTheme()
    {
        return $this->resolveThemeColor($this->theme);
    }

    /**
     * Make the accessible label for the underlying progress bar.
     *
     * @return string
     */
    public function makeBarLabel()
    {
        return empty($this->label)
            ? __('adminlte::adminlte.progress')
            : $this->label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.progress-group');
    }
}
