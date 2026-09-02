<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Toast extends Component
{
    use HandlesThemeColors;

    /**
     * The screen position of the shared toast container, with the set of
     * Bootstrap utilities that place it.
     *
     * @var array
     */
    protected static $positions = [
        'top-start' => 'top-0 start-0',
        'top-center' => 'top-0 start-50 translate-middle-x',
        'top-end' => 'top-0 end-0',
        'middle-start' => 'top-50 start-0 translate-middle-y',
        'middle-center' => 'top-50 start-50 translate-middle',
        'middle-end' => 'top-50 end-0 translate-middle-y',
        'bottom-start' => 'bottom-0 start-0',
        'bottom-center' => 'bottom-0 start-50 translate-middle-x',
        'bottom-end' => 'bottom-0 end-0',
    ];

    /**
     * The default screen position for the toasts.
     *
     * @var string
     */
    protected const DEFAULT_POSITION = 'bottom-end';

    /**
     * The id attribute for the underlying toast element. It is required to
     * target the toast from a trigger control or from javascript.
     *
     * @var string
     */
    public $id;

    /**
     * The toast theme (light, dark, primary, secondary, info, success, warning
     * or danger). Note the AdminLTE v4 stylesheet only provides a
     * '.toast-{color}' variant for the Bootstrap theme colors.
     *
     * @var string
     */
    public $theme;

    /**
     * The title for the toast header.
     *
     * @var string
     */
    public $title;

    /**
     * An icon for the toast header (a Bootstrap Icon).
     *
     * @var string
     */
    public $icon;

    /**
     * The timestamp hint for the toast header.
     *
     * @var string
     */
    public $time;

    /**
     * The screen position of the shared container holding the toast.
     *
     * @var string
     */
    public $position;

    /**
     * Whether the toast hides itself after the delay time. When not provided,
     * the Bootstrap default is used (the toast hides itself).
     *
     * @var bool|null
     */
    public $autohide;

    /**
     * The time (in milliseconds) the toast stays visible before hiding itself.
     * When not provided, the Bootstrap default is used.
     *
     * @var int|null
     */
    public $delay;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id = null, $theme = null, $title = null, $icon = null, $time = null,
        $position = null, $autohide = null, $delay = null
    ) {
        $this->id = $id;
        $this->theme = $theme;
        $this->icon = $icon;
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->time = UtilsHelper::applyHtmlEntityDecoder($time);
        $this->position = $this->makePosition($position);

        $this->autohide = isset($autohide)
            ? filter_var($autohide, FILTER_VALIDATE_BOOLEAN)
            : null;

        $this->delay = isset($delay) ? intval($delay) : null;
    }

    /**
     * Resolve the screen position of the shared toast container. An unknown
     * position falls back to the default one.
     *
     * @param  string|null  $position  The requested screen position
     * @return string
     */
    protected function makePosition($position)
    {
        return isset(self::$positions[$position])
            ? $position
            : self::DEFAULT_POSITION;
    }

    /**
     * Make the id attribute for the shared toast container. There is one
     * container per screen position, shared by every toast placed on it.
     *
     * @return string
     */
    public function makeContainerId()
    {
        return "adminlte-toast-container-{$this->position}";
    }

    /**
     * Make the class attribute for the shared toast container.
     *
     * @return string
     */
    public function makeContainerClass()
    {
        $classes = ['toast-container position-fixed'];
        $classes[] = self::$positions[$this->position];
        $classes[] = 'p-3';

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the toast.
     *
     * @return string
     */
    public function makeToastClass()
    {
        $classes = ['toast'];

        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "toast-{$theme}";
        }

        // Without a header, the dismiss button is placed next to the body, so
        // the Bootstrap flexbox alignment utility is required.

        if (! $this->hasHeader()) {
            $classes[] = 'align-items-center';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the default attributes for the toast element.
     *
     * @return array
     */
    public function makeToastDefaultAttrs()
    {
        $attrs = [
            'class' => $this->makeToastClass(),
            'role' => 'alert',
            'aria-live' => 'assertive',
            'aria-atomic' => 'true',
            'data-adminlte-toast-container' => $this->makeContainerId(),
        ];

        if (! empty($this->id)) {
            $attrs['id'] = $this->id;
        }

        if (isset($this->autohide)) {
            $attrs['data-bs-autohide'] = $this->autohide ? 'true' : 'false';
        }

        if (isset($this->delay)) {
            $attrs['data-bs-delay'] = $this->delay;
        }

        return $attrs;
    }

    /**
     * Check whether the toast provides a header.
     *
     * @return bool
     */
    public function hasHeader()
    {
        return ! empty($this->title)
            || ! empty($this->icon)
            || ! empty($this->time);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.toast');
    }
}
