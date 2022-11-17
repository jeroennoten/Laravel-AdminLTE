<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class ProfileWidget extends Component
{
    /**
     * The user name of the profile widget.
     *
     * @var string
     */
    public $name;

    /**
     * The user description of the profile widget.
     *
     * @var string
     */
    public $desc;

    /**
     * The user image of the profile widget.
     *
     * @var string
     */
    public $img;

    /**
     * The profile header theme (light, dark, primary, secondary, info, success,
     * warning, danger or any other AdminLTE color like lighblue or teal).
     *
     * @var string
     */
    public $theme;

    /**
     * The profile header image cover. Overlays the header theme.
     *
     * @var string
     */
    public $cover;

    /**
     * Extra classes for the profile header. This provides a way to customize
     * the header style.
     *
     * @var string
     */
    public $headerClass;

    /**
     * Extra classes for the profile footer. This provides a way to customize
     * the footer style.
     *
     * @var string
     */
    public $footerClass;

    /**
     * The profile header layout type (modern or classic).
     *
     * @var string
     */
    public $layoutType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = null, $desc = null, $img = null, $theme = null, $cover = null,
        $headerClass = null, $footerClass = null, $layoutType = 'modern'
    ) {
        $this->name = UtilsHelper::applyHtmlEntityDecoder($name);
        $this->desc = UtilsHelper::applyHtmlEntityDecoder($desc);
        $this->img = $img;
        $this->theme = $theme;
        $this->cover = $cover;
        $this->headerClass = $headerClass;
        $this->footerClass = $footerClass;

        // Setup the header layout type.

        $this->layoutType = $layoutType;

        if (! in_array($this->layoutType, ['classic', 'modern'])) {
            $this->layoutType = 'modern';
        }
    }

    /**
     * Make the profile card class.
     *
     * @return string
     */
    public function makeCardClass()
    {
        $classes = ['card', 'card-widget'];

        if ($this->layoutType === 'modern') {
            $classes[] = 'widget-user';
        } elseif ($this->layoutType === 'classic') {
            $classes[] = 'widget-user-2';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the profile header class.
     *
     * @return string
     */
    public function makeHeaderClass()
    {
        $classes = ['widget-user-header'];

        if (isset($this->theme) && empty($this->cover)) {
            $classes[] = "bg-gradient-{$this->theme}";
        }

        if (! empty($this->headerClass)) {
            $classes[] = $this->headerClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the profile header style.
     *
     * @return string
     */
    public function makeHeaderStyle()
    {
        $style = [];

        if (! empty($this->cover)) {
            $style[] = "background: url('{$this->cover}') center center";
        }

        return implode(';', $style);
    }

    /**
     * Make the profile footer class.
     *
     * @return string
     */
    public function makeFooterClass()
    {
        $classes = ['card-footer'];

        if (! empty($this->footerClass)) {
            $classes[] = $this->footerClass;
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
        return view('adminlte::components.widget.profile-widget');
    }
}
