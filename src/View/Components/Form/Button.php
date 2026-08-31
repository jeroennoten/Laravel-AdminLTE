<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\HandlesThemeColors;

class Button extends Component
{
    use HandlesThemeColors;

    /**
     * The visible label (text) for the button.
     *
     * @var string
     */
    public $label;

    /**
     * The button type ('button', 'submit', 'reset'). Similar to the html type
     * attribute but with a default value.
     *
     * @var string
     */
    public $type;

    /**
     * The button style theme. One of the available Bootstrap 5 themes:
     * primary, secondary, info, warning, danger, success, light, dark, etc.
     *
     * @var string
     */
    public $theme;

    /**
     * A Bootstrap Icon for the button (ie. 'bi bi-check-lg').
     *
     * @var string
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = null, $type = 'button', $theme = 'default', $icon = null
    ) {
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->type = $type;
        $this->theme = $this->normalizeTheme($theme);
        $this->icon = $icon;
    }

    /**
     * Normalize the button theme. Bootstrap 5 (and therefore AdminLTE v4) does
     * not provide a 'btn-default' class anymore, so the legacy 'default' theme
     * is mapped to the Bootstrap 5 'secondary' theme.
     *
     * @param  string  $theme  The theme provided by the user
     * @return string
     */
    protected function normalizeTheme($theme)
    {
        if ($theme === 'default') {
            return 'secondary';
        }

        // Resolve the legacy AdminLTE v3 color names, so a button themed with
        // one of them keeps working with the v4 palette.

        return $this->resolveThemeColor($theme);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.button');
    }
}
