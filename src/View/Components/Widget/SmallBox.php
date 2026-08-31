<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class SmallBox extends Component
{
    use HandlesThemeColors;

    /**
     * The title/header for the box.
     *
     * @var string
     */
    public $title;

    /**
     * The text/description for the box.
     *
     * @var string
     */
    public $text;

    /**
     * A Bootstrap Icon for the box.
     *
     * @var string
     */
    public $icon;

    /**
     * The box theme (light, dark, primary, secondary, info, success, warning,
     * danger or any color of the AdminLTE extended palette like sky or teal).
     *
     * @var string
     */
    public $theme;

    /**
     * An url for the box. When enabled, a link-styled footer section will be
     * visible pointing to that url.
     *
     * @var string
     */
    public $url;

    /**
     * A text/label associated with the footer url.
     *
     * @var string
     */
    public $urlText;

    /**
     * Indicates if the box is loading. When enabled, an overlay with a loading
     * icon will show over the box.
     *
     * @var mixed
     */
    public $loading;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $icon = null, $theme = null,
        $url = null, $urlText = null, $loading = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->icon = $icon;
        $this->theme = $theme;
        $this->url = $url;
        $this->urlText = UtilsHelper::applyHtmlEntityDecoder($urlText);
        $this->loading = $loading;
    }

    /**
     * Make the box class.
     *
     * @return string
     */
    public function makeBoxClass()
    {
        $classes = ['small-box'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the box icon.
     *
     * @return string
     */
    public function makeIconClass()
    {
        // Note the reference uses an svg of exactly 70px, while an icon font
        // inherits the line height of the body and would overflow that box.

        $classes = ['small-box-icon', 'lh-1'];

        if (! empty($this->icon)) {
            $classes[] = $this->icon;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the loading overlay class.
     *
     * @return string
     */
    public function makeOverlayClass()
    {
        // Note the AdminLTE v3 '.overlay' class no longer exists on v4, the
        // overlay is built with Bootstrap utilities. The 'small-box-overlay'
        // class is only kept as a hook for the javascript helper.

        $classes = [
            'small-box-overlay', 'position-absolute', 'top-0', 'start-0',
            'w-100', 'h-100', 'd-flex', 'align-items-center',
            'justify-content-center', 'bg-body', 'bg-opacity-75', 'rounded',
        ];

        if (! isset($this->loading)) {
            $classes[] = 'd-none';
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
        return view('adminlte::components.widget.small-box');
    }
}
