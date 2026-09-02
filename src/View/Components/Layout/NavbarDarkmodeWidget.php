<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Layout;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper;

class NavbarDarkmodeWidget extends Component
{
    /**
     * The icon to use when the light color mode is active. Note this icon is
     * also used as the icon of the widget when dark mode is disabled.
     *
     * @var string
     */
    public $iconDisabled = 'bi bi-sun-fill';

    /**
     * The icon to use when the dark color mode is active. Note this icon is
     * also used as the icon of the widget when dark mode is enabled.
     *
     * @var string
     */
    public $iconEnabled = 'bi bi-moon-fill';

    /**
     * The icon to use when the automatic color mode is active. The automatic
     * mode follows the operating system preference of the visitor.
     *
     * @var string
     */
    public $iconAuto = 'bi bi-circle-half';

    /**
     * The color mode resolved for the current request.
     *
     * @var string
     */
    protected $colorMode;

    /**
     * The color to use for the icon when dark mode is disabled.
     *
     * @var string
     */
    public $colorDisabled;

    /**
     * The color to use for the icon when dark mode is enabled.
     *
     * @var string
     */
    public $colorEnabled;

    /**
     * The color to use for the icon when the automatic mode is active.
     *
     * @var string
     */
    public $colorAuto;

    /**
     * Whether the widget provides the full AdminLTE v4 color mode selector
     * (light, dark and auto) or the legacy two-states toggle.
     *
     * @var bool
     */
    public $dropdownMode;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $iconDisabled = null, $iconEnabled = null, $iconAuto = null,
        $colorDisabled = null, $colorEnabled = null, $colorAuto = null,
        $dropdownMode = null
    ) {
        // Setup the icon to use when dark mode is disabled.

        if (! empty($iconDisabled)) {
            $this->iconDisabled = $iconDisabled;
        }

        // Setup the icon to use when dark mode is enabled.

        if (! empty($iconEnabled)) {
            $this->iconEnabled = $iconEnabled;
        }

        // Setup the icon to use for the automatic color mode.

        if (! empty($iconAuto)) {
            $this->iconAuto = $iconAuto;
        }

        // Setup the icon colors.

        $this->colorDisabled = $colorDisabled;
        $this->colorEnabled = $colorEnabled;
        $this->colorAuto = $colorAuto;

        // Setup the widget mode. The color mode selector requires the client
        // side persistence provided by the AdminLTE color mode plugin, so the
        // legacy toggle is used when that persistence is disabled.

        $this->dropdownMode = is_null($dropdownMode)
            ? (bool) config('adminlte.color_mode.remember', true)
            : (bool) $dropdownMode;
    }

    /**
     * Make the class attribute for the widget icon. Note this is only used by
     * the legacy toggle mode, the color mode selector swaps the icons on the
     * client side.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = $this->currentColorMode() === 'dark'
            ? $this->makeIconEnabledClass()
            : $this->makeIconDisabledClass();

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the icon when dark mode is disabled.
     *
     * @return array
     */
    public function makeIconDisabledClass()
    {
        return $this->makeIconClasses($this->iconDisabled, $this->colorDisabled);
    }

    /**
     * Make the class attribute for the icon when dark mode is enabled.
     *
     * @return array
     */
    public function makeIconEnabledClass()
    {
        return $this->makeIconClasses($this->iconEnabled, $this->colorEnabled);
    }

    /**
     * Make the class attribute for the icon of the automatic color mode.
     *
     * @return array
     */
    public function makeIconAutoClass()
    {
        return $this->makeIconClasses($this->iconAuto, $this->colorAuto);
    }

    /**
     * Gets the currently configured color mode. The resolved mode is kept on
     * the component, since the view reads it on every entry of the selector
     * and resolving it may dispatch the 'ReadingDarkModePreference' event.
     *
     * @return string
     */
    public function currentColorMode()
    {
        if (! isset($this->colorMode)) {
            $this->colorMode = LayoutHelper::getColorMode();
        }

        return $this->colorMode;
    }

    /**
     * Make the set of classes for the specified icon and color.
     *
     * @param  string  $icon  The icon classes
     * @param  string|null  $color  The (optional) icon color
     * @return array
     */
    protected function makeIconClasses($icon, $color)
    {
        $classes = explode(' ', $icon);

        if (! empty($color)) {
            $classes[] = "text-{$color}";
        }

        return $classes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.navbar-darkmode-widget');
    }
}
