<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Alert extends Component
{
    use HandlesThemeColors;

    /**
     * The default icon for each alert theme (Bootstrap Icons).
     *
     * @var array
     */
    protected $icons = [
        'dark' => 'bi bi-lightning-fill',
        'light' => 'bi bi-lightbulb',
        'primary' => 'bi bi-bell-fill',
        'secondary' => 'bi bi-tag-fill',
        'info' => 'bi bi-info-circle-fill',
        'success' => 'bi bi-check-circle-fill',
        'warning' => 'bi bi-exclamation-triangle-fill',
        'danger' => 'bi bi-x-octagon-fill',
    ];

    /**
     * The alert icon (a Bootstrap Icon).
     *
     * @var string
     */
    public $icon;

    /**
     * The alert theme (dark, light, primary, secondary, info, success, warning
     * or danger).
     *
     * @var string
     */
    public $theme;

    /**
     * The alert title.
     *
     * @var string
     */
    public $title;

    /**
     * Indicates if the alert is dismissable.
     *
     * @var bool|mixed
     */
    public $dismissable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $theme = null, $icon = null, $title = null, $dismissable = null
    ) {
        $this->theme = $theme;
        $this->icon = $icon;
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->dismissable = $dismissable;

        // When a theme is provided, use the default theme icon if no other
        // icon is provided.

        if (! isset($icon) && ! empty($theme)) {
            $this->icon = $this->icons[$theme] ?? null;
        }
    }

    /**
     * Make the class attribute for the alert item.
     *
     * @return string
     */
    public function makeAlertClass()
    {
        $classes = ['alert'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "alert-{$theme}";
        } else {
            $classes[] = 'border';
        }

        // Note the Bootstrap v5 markup requires the 'fade' and 'show' classes
        // in order to animate the dismiss of the alert.

        if (! empty($this->dismissable)) {
            $classes[] = 'alert-dismissible';
            $classes[] = 'fade';
            $classes[] = 'show';
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
        return view('adminlte::components.widget.alert');
    }
}
