<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Progress extends Component
{
    use HandlesThemeColors;

    /**
     * The available progress bar sizes.
     *
     * @var array
     */
    protected $pSizes = ['sm', 'xs', 'xxs'];

    /**
     * The progress bar percentage value (integer between 0 and 100).
     *
     * @var int
     */
    public $value;

    /**
     * The progress bar theme (light, dark, primary, secondary, info, success,
     * warning, danger or any color of the AdminLTE extended palette like sky
     * or teal). Set to an empty value to inherit the color of the container.
     *
     * @var string
     */
    public $theme;

    /**
     * The progress bar size (sm, xs or xxs).
     *
     * @var string
     */
    public $size;

    /**
     * Indicates if the progress bar have stripes.
     *
     * @var bool|mixed
     */
    public $striped;

    /**
     * Indicates if the progress bar is animated.
     *
     * @var bool|mixed
     */
    public $animated;

    /**
     * Indicates if the progress bar is vertical.
     *
     * @var bool|mixed
     */
    public $vertical;

    /**
     * Enables the progress bar label.
     *
     * @var bool|mixed
     */
    public $withLabel;

    /**
     * The set of segments of a stacked progress bar. When provided, the
     * component emits the Bootstrap 5.3 "progress-stacked" markup, where
     * every segment is a ".progress" element of its own holding a full width
     * ".progress-bar". Each entry accepts a 'value', an optional 'theme', an
     * optional 'label' and optional 'striped' and 'animated' flags.
     *
     * @var array
     */
    public $segments;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $value = 0, $theme = 'info', $size = null, $striped = null,
        $vertical = null, $animated = null, $withLabel = null,
        $segments = null
    ) {
        // Setup the value property, to be between 0 and 100.

        $this->value = max(min($value, 100), 0);

        // Initialize other properties.

        $this->theme = $theme;
        $this->size = $size;
        $this->striped = $striped;
        $this->animated = $animated;
        $this->vertical = $vertical;
        $this->withLabel = $withLabel;
        $this->segments = $this->resolveSegments($segments);
    }

    /**
     * Resolve the set of segments into a normalized array of items. Every
     * item is guaranteed to hold a 'value' between 0 and 100, a 'theme', a
     * 'label' and the 'striped' and 'animated' flags. The component level
     * theme and flags are used for the values a segment does not provide.
     *
     * @param  mixed  $segments  The set of segments requested by the user
     * @return array
     */
    protected function resolveSegments($segments)
    {
        if (! is_array($segments) || empty($segments)) {
            return [];
        }

        $items = [];

        foreach ($segments as $segment) {
            if (! is_array($segment)) {
                $segment = ['value' => $segment];
            }

            $label = $segment['label'] ?? null;

            $items[] = [
                'value' => max(min($segment['value'] ?? 0, 100), 0),
                'theme' => array_key_exists('theme', $segment)
                    ? $segment['theme']
                    : $this->theme,
                'label' => isset($label)
                    ? UtilsHelper::applyHtmlEntityDecoder($label)
                    : null,
                'striped' => $this->resolveSegmentFlag($segment, 'striped'),
                'animated' => $this->resolveSegmentFlag($segment, 'animated'),
            ];
        }

        return $items;
    }

    /**
     * Resolve one of the boolean flags of a segment. When the segment does
     * not provide the flag, the component level one is used.
     *
     * @param  array  $segment  The segment requested by the user
     * @param  string  $flag  The name of the flag to resolve
     * @return bool|null
     */
    protected function resolveSegmentFlag($segment, $flag)
    {
        $value = array_key_exists($flag, $segment)
            ? $segment[$flag]
            : $this->{$flag};

        return empty($value) ? null : true;
    }

    /**
     * Check if the component renders a stacked progress bar.
     *
     * @return bool
     */
    public function isStacked()
    {
        return ! empty($this->segments);
    }

    /**
     * Make the class attribute for the main progress item.
     *
     * @return string
     */
    public function makeProgressClass()
    {
        $classes = [$this->isStacked() ? 'progress-stacked' : 'progress'];

        // The AdminLTE v4 stylesheet gives the progress bars no margin, the
        // reference layouts separate the stacked ones with a 'mb-2' utility.
        // It is only added when the caller provides no margin of its own.

        if (! UtilsHelper::hasBottomMarginClass($this->attributes?->get('class'))) {
            $classes[] = 'mb-2';
        }

        if (isset($this->size) && in_array($this->size, $this->pSizes)) {
            $classes[] = "progress-{$this->size}";
        }

        // The vertical mode is an AdminLTE modifier of a single '.progress'
        // track, the Bootstrap stacked layout has no vertical counterpart.

        if (isset($this->vertical) && ! $this->isStacked()) {
            $classes[] = 'vertical';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the track of a stacked segment. The size
     * is repeated on every track, the height of a '.progress' element is not
     * inherited from the stacked container.
     *
     * @return string
     */
    public function makeSegmentClass()
    {
        $classes = ['progress'];

        if (isset($this->size) && in_array($this->size, $this->pSizes)) {
            $classes[] = "progress-{$this->size}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the bar of a stacked segment.
     *
     * @param  array  $segment  A normalized segment item
     * @return string
     */
    public function makeSegmentBarClass($segment)
    {
        $classes = ['progress-bar', 'fw-bold'];
        $theme = $this->resolveThemeColor($segment['theme']);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        if (isset($segment['striped']) || isset($segment['animated'])) {
            $classes[] = 'progress-bar-striped';
        }

        if (isset($segment['animated'])) {
            $classes[] = 'progress-bar-animated';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the style attribute for the track of a stacked segment. On the
     * Bootstrap stacked markup the percentage lives on the track, the inner
     * bar always fills it.
     *
     * @param  array  $segment  A normalized segment item
     * @return string
     */
    public function makeSegmentStyle($segment)
    {
        return "width:{$segment['value']}%";
    }

    /**
     * Make the label of a stacked segment. A segment without an explicit
     * label falls back to the percentage one when the component enables the
     * labels, and stays empty otherwise.
     *
     * @param  array  $segment  A normalized segment item
     * @return string|null
     */
    public function makeSegmentLabel($segment)
    {
        if (isset($segment['label'])) {
            return $segment['label'];
        }

        return $this->isSegmentLabelAuto($segment)
            ? "{$segment['value']}%"
            : null;
    }

    /**
     * Check if a stacked segment holds the built-in percentage label, which
     * is the only one refreshed by the Javascript utility class.
     *
     * @param  array  $segment  A normalized segment item
     * @return bool
     */
    public function isSegmentLabelAuto($segment)
    {
        return ! isset($segment['label']) && isset($this->withLabel);
    }

    /**
     * Make the accessible label for the track of a stacked segment.
     *
     * @param  array  $segment  A normalized segment item
     * @return string
     */
    public function makeSegmentAriaLabel($segment)
    {
        return $segment['label'] ?? __('adminlte::adminlte.progress');
    }

    /**
     * Make the class attribute for the progress bar item.
     *
     * @return string
     */
    public function makeProgressBarClass()
    {
        $classes = ['progress-bar', 'fw-bold'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        if (isset($this->striped) || isset($this->animated)) {
            $classes[] = 'progress-bar-striped';
        }

        if (isset($this->animated)) {
            $classes[] = 'progress-bar-animated';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the style attribute for the progress bar item.
     *
     * @return string
     */
    public function makeProgressBarStyle()
    {
        $styles = [];

        if (isset($this->vertical)) {
            $styles[] = "height:{$this->value}%";
        } else {
            $styles[] = "width:{$this->value}%";
        }

        return implode(';', $styles);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.progress');
    }
}
