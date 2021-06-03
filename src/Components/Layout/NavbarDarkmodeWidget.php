<?php

namespace JeroenNoten\LaravelAdminLte\Components\Layout;

use Illuminate\View\Component;

class NavbarDarkmodeWidget extends Component
{
    /**
     * The Font Awesome icon to use when darkmode is disabled.
     *
     * @var string
     */
    public $iconDisabled = 'far fa-moon';

    /**
     * The Font Awesome icon to use when darkmode is enabled.
     *
     * @var string
     */
    public $iconEnabled = 'fas fa-moon';

    /**
     * The AdminLTE color to use for the icon when darkmode is disabled.
     *
     * @var string
     */
    public $colorDisabled;

    /**
     * The AdminLTE color to use for the icon when darkmode is enabled.
     *
     * @var string
     */
    public $colorEnabled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $iconDisabled = null, $iconEnabled = null,
        $colorDisabled = null, $colorEnabled = null
    ) {
        // Setup the icon to use when darkmode is disabled.

        if (! empty($iconDisabled)) {
            $this->iconDisabled = $iconDisabled;
        }

        // Setup the icon to use when darkmode is enabled.

        if (! empty($iconEnabled)) {
            $this->iconEnabled = $iconEnabled;
        }

        // Setup the icon colors.

        $this->colorDisabled = $colorDisabled;
        $this->colorEnabled = $colorEnabled;
    }

    /**
     * Make the class attribute for the darkmode widget icon.
     *
     * @return string
     */
    public function makeIconClass()
    {
        // Read the related configuration value and get classes for the icon.

        if (config('adminlte.layout_dark_mode', false)) {
            $classes = $this->makeIconEnabledClass();
        } else {
            $classes = $this->makeIconDisabledClass();
        }

        // Return the icon classes.

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the icon when darkmode is disabled.
     *
     * @return string
     */
    public function makeIconDisabledClass()
    {
        $classes = explode(' ', $this->iconDisabled);

        if (! empty($this->colorDisabled)) {
            $classes[] = "text-{$this->colorDisabled}";
        }

        // Return the icon classes.

        return $classes;
    }

    /**
     * Make the class attribute for the icon when darkmode is enabled.
     *
     * @return string
     */
    public function makeIconEnabledClass()
    {
        $classes = explode(' ', $this->iconEnabled);

        if (! empty($this->colorEnabled)) {
            $classes[] = "text-{$this->colorEnabled}";
        }

        // Return the icon classes.

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
