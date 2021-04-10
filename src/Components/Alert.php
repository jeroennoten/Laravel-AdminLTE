<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

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
        $theme = 'info', $icon = null, $title = null, $dismissable = null
    ) {
        $this->theme = $theme;
        $this->icon = $icon ?? $this->icons[$theme] ?? null;
        $this->title = $title;
        $this->dismissable = $dismissable;
    }

    /**
     * Make the class attribute for the alert item.
     *
     * @return string
     */
    public function makeAlertClass()
    {
        $classes = ['alert', "alert-{$this->theme}"];

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
        return view('adminlte::components.alert');
    }
}
