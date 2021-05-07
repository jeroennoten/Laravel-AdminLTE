<?php

namespace JeroenNoten\LaravelAdminLte\Components\Layout;

use Illuminate\View\Component;

class NavbarNotificationLink extends Component
{
    /**
     * The id attribute for the underlying <li> wrapper.
     *
     * @var string
     */
    public $id;

    /**
     * The notification icon (a Font Awesome icon).
     *
     * @var string
     */
    public $icon;

    /**
     * The notification icon color (an AdminLTE color).
     *
     * @var string
     */
    public $iconColor;

    /**
     * The label for the notification badge.
     *
     * @var string
     */
    public $badgeLabel;

    /**
     * The background color for the notification badge (an AdminLTE color).
     *
     * @var string
     */
    public $badgeColor;

    /**
     * An array with the update configuration. The valid properties are:
     * url => string/array representing the url to fetch for new data.
     * route => string/array representing the route to fetch for new data.
     * period => integer representing the updating period time (in seconds).
     *
     * @var array
     */
    public $updateCfg;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id, $icon, $iconColor = null, $badgeLabel = null, $badgeColor = null,
        $updateCfg = []
    ) {
        $this->id = $id;
        $this->icon = $icon;
        $this->iconColor = $iconColor;
        $this->badgeLabel = $badgeLabel;
        $this->badgeColor = $badgeColor;
        $this->updateCfg = is_array($updateCfg) ? $updateCfg : [];
    }

    /**
     * Make the class attribute for the notification icon.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = [$this->icon];

        if (! empty($this->iconColor)) {
            $classes[] = "text-{$this->iconColor}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the notification badge.
     *
     * @return string
     */
    public function makeBadgeClass()
    {
        $classes = ['badge navbar-badge text-bold text-xs badge-pill'];

        if (! empty($this->badgeColor)) {
            $classes[] = "badge-{$this->badgeColor}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the period time for updating the notification badge.
     *
     * @return int
     */
    public function makeUpdatePeriod()
    {
        if (! isset($this->updateCfg['period'])) {
            return 0;
        }

        return (intval($this->updateCfg['period']) ?? 0) * 1000;
    }

    /**
     * Make the url to use for fetch new notification data.
     *
     * @return string|null
     */
    public function makeUpdateUrl()
    {
        // Check if url property is available.

        if (! empty($this->updateCfg['url'])) {
            $uParams = $this->updateCfg['url'];

            return is_array($uParams) ? url(...$uParams) : url($uParams);
        }

        // Check if route property is available.

        if (! empty($this->updateCfg['route'])) {
            $rParams = $this->updateCfg['route'];

            return is_array($rParams) ? route(...$rParams) : route($rParams);
        }

        // Return null when no url was configured.

        return null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.navbar-notification-link');
    }
}
