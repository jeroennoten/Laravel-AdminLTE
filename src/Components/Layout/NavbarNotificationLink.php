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
     * The url to query for new data in order to update the notification.
     *
     * @var string
     */
    public $updateUrl;

    /**
     * The time interval (in seconds) for query the update url.
     *
     * @var int
     */
    public $updateTime;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id, $icon, $iconColor = null, $badgeLabel = null, $badgeColor = null,
        $updateUrl = null, $updateTime = null
    ) {
        $this->id = $id;
        $this->icon = $icon;
        $this->iconColor = $iconColor;
        $this->badgeLabel = $badgeLabel;
        $this->badgeColor = $badgeColor;
        $this->updateUrl = $updateUrl;
        $this->updateTime = isset($updateTime) ? intval($updateTime) : 0;
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
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.navbar-notification-link');
    }
}
