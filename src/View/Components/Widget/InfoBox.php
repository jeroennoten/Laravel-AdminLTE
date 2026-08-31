<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class InfoBox extends Component
{
    use HandlesThemeColors;

    /**
     * The title/header for the box.
     *
     * @var string
     */
    public $title;

    /**
     * A short text description for the box.
     *
     * @var string
     */
    public $text;

    /**
     * A long description for the box.
     *
     * @var string
     */
    public $description;

    /**
     * A Bootstrap Icon for the box.
     *
     * @var string
     */
    public $icon;

    /**
     * An URL for the box.
     *
     * @var string
     */
    public $url;

    /**
     * The target element of the box for the URL (title or text).
     *
     * @var string
     */
    public $urlTarget;

    /**
     * The box theme (light, dark, primary, secondary, info, success, warning,
     * danger or any color of the AdminLTE extended palette like sky or teal).
     *
     * @var string
     */
    public $theme;

    /**
     * The icon theme (light, dark, primary, secondary, info, success, warning,
     * danger or any color of the AdminLTE extended palette like sky or teal).
     *
     * @var string
     */
    public $iconTheme;

    /**
     * Enables a progress bar for the box. The value should be an integer
     * indicating the percentage of the progress bar.
     *
     * @var int
     */
    public $progress;

    /**
     * The progress bar theme (light, dark, primary, secondary, info, success,
     * warning, danger or any color of the AdminLTE extended palette like sky
     * or teal). When not defined, a themed box will paint the progress bar
     * with its own contrast color (the AdminLTE v4 default).
     *
     * @var string
     */
    public $progressTheme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $icon = null, $description = null,
        $url = null, $urlTarget = 'title', $theme = null, $iconTheme = null,
        $progress = null, $progressTheme = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->icon = $icon;
        $this->description = UtilsHelper::applyHtmlEntityDecoder($description);
        $this->url = $url;
        $this->urlTarget = $urlTarget;
        $this->theme = $theme;
        $this->iconTheme = $iconTheme;

        // Setup the progress property, to be between 0 and 100 when defined.

        $this->progress = isset($progress)
            ? max(min($progress, 100), 0)
            : null;

        $this->progressTheme = $progressTheme;
    }

    /**
     * Make the box class.
     *
     * @return string
     */
    public function makeBoxClass()
    {
        $classes = ['info-box'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the icon container class.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = ['info-box-icon'];
        $iconTheme = $this->resolveThemeColor($this->iconTheme);

        if (! empty($iconTheme)) {
            $classes[] = "text-bg-{$iconTheme}";
            $classes[] = 'shadow-sm';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the theme for the underlying progress bar. When no theme is setup
     * and the box is themed, an empty theme is returned, so the AdminLTE v4
     * stylesheet can paint the bar with the box contrast color.
     *
     * @return string
     */
    public function makeProgressTheme()
    {
        if (! empty($this->progressTheme)) {
            return $this->progressTheme;
        }

        return empty($this->theme) ? 'primary' : '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.info-box');
    }
}
