<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Alert extends Component
{
    /**
     * The default icon for each alert theme.
     *
     * @var array
     */
    protected $icons = [
        'dark'      => 'fas fa-bolt',
        'light'     => 'far fa-lightbulb',
        'primary'   => 'fas fa-bell',
        'secondary' => 'fas fa-tag',
        'info'      => 'fas fa-info-circle',
        'success'   => 'fas fa-check-circle',
        'warning'   => 'fas fa-exclamation-triangle',
        'danger'    => 'fas fa-ban',
    ];

    /**
     * The alert icon (a Font Awesome icon).
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
            $this->icon = $this->icons[$theme];
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

        if (! empty($this->theme)) {
            $classes[] = "alert-{$this->theme}";
        } else {
            $classes[] = 'border';
        }

        if (! empty($this->dismissable)) {
            $classes[] = 'alert-dismissable';
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
