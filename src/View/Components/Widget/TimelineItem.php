<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class TimelineItem extends Component
{
    use HandlesThemeColors;

    /**
     * A Bootstrap Icon for the round marker attached to the timeline line.
     *
     * @var string
     */
    public $icon;

    /**
     * The theme for the round marker (light, dark, primary, secondary, info,
     * success, warning or danger). Any color of the AdminLTE extended palette
     * (navy, sky, teal, ...) is also supported when the
     * 'adminlte.assets.extended_colors' option is enabled. The AdminLTE v3
     * color names (lightblue, maroon, ...) are still accepted and translated
     * to their v4 equivalent.
     *
     * @var string
     */
    public $iconTheme;

    /**
     * The time (or elapsed time) shown on the top right corner of the item.
     *
     * @var string
     */
    public $time;

    /**
     * A Bootstrap Icon shown next to the time. Use an empty value to render
     * the time without any icon.
     *
     * @var string
     */
    public $timeIcon;

    /**
     * The text for the item header. When the 'headerSlot' is defined, the slot
     * takes precedence over this text.
     *
     * @var string
     */
    public $header;

    /**
     * An URL for the item.
     *
     * @var string
     */
    public $url;

    /**
     * The target element of the item for the URL (header or time).
     *
     * @var string
     */
    public $urlTarget;

    /**
     * Indicates if the separator between the item header and the item body is
     * removed. Useful on the items that have no body and no footer.
     *
     * @var bool|mixed
     */
    public $noBorder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $icon = null, $iconTheme = null, $time = null,
        $timeIcon = 'bi bi-clock-fill', $header = null, $url = null,
        $urlTarget = 'header', $noBorder = null
    ) {
        $this->icon = $icon;
        $this->iconTheme = $iconTheme;
        $this->time = UtilsHelper::applyHtmlEntityDecoder($time);
        $this->timeIcon = $timeIcon;
        $this->header = UtilsHelper::applyHtmlEntityDecoder($header);
        $this->url = $url;
        $this->urlTarget = $urlTarget;
        $this->noBorder = $noBorder;
    }

    /**
     * Make the class attribute for the round marker of the item.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = ['timeline-icon'];

        if (! empty($this->icon)) {
            $classes[] = $this->icon;
        }

        $theme = $this->resolveThemeColor($this->iconTheme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the item header.
     *
     * @return string
     */
    public function makeHeaderClass()
    {
        $classes = ['timeline-header'];

        // The AdminLTE v3 '.no-border' modifier of the timeline header does
        // not exist on the v4 stylesheet, the separator is removed with the
        // Bootstrap 5 'border-bottom-0' utility instead.

        if ($this->noBorder) {
            $classes[] = 'border-bottom-0';
        }

        return implode(' ', $classes);
    }

    /**
     * Check if the item header is empty (no content defined for the header).
     *
     * @param  bool  $hasSlot  Whether the item header slot is defined
     * @return bool
     */
    public function isHeaderEmpty($hasSlot = false)
    {
        return empty($this->header) && ! $hasSlot;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.timeline-item');
    }
}
