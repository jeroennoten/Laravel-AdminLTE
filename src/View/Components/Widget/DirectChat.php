<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class DirectChat extends Component
{
    use HandlesThemeColors;

    /**
     * The set of accepted timestamp contrast modes. They map to the
     * '.direct-chat.timestamp-{mode}' rules of the AdminLTE v4 stylesheet,
     * which only change the opacity applied to the timestamp color.
     *
     * @var array
     */
    protected const TIMESTAMP_MODES = ['light', 'dark'];

    /**
     * The default theme of the widget. Unlike the other card flavors, the
     * direct chat can not be left without a theme: the bubble of an outgoing
     * message is painted with the '--lte-direct-chat-bg' custom property, and
     * that property is only declared by the '.direct-chat-{color}' variants.
     *
     * @var string
     */
    protected const DEFAULT_THEME = 'primary';

    /**
     * The title for the card header.
     *
     * @var string
     */
    public $title;

    /**
     * A Bootstrap Icon for the card header.
     *
     * @var string
     */
    public $icon;

    /**
     * The widget theme (light, dark, primary, secondary, info, success,
     * warning or danger). Any color of the AdminLTE extended palette (navy,
     * sky, teal, ...) is also supported when the
     * 'adminlte.assets.extended_colors' option is enabled. The AdminLTE v3
     * color names (lightblue, maroon, ...) are still accepted and translated
     * to their v4 equivalent.
     *
     * @var string
     */
    public $theme;

    /**
     * The content of the badge shown on the card header, usually the amount
     * of unread messages.
     *
     * @var string
     */
    public $badge;

    /**
     * The theme of the header badge. It falls back to the widget theme when
     * not provided.
     *
     * @var string
     */
    public $badgeTheme;

    /**
     * The height of the messages pane and of the contacts pane. A bare number
     * is taken as pixels. Both panes always get the same height, otherwise
     * they desynchronize when the contacts pane slides in.
     *
     * @var string
     */
    public $height;

    /**
     * The contrast mode of the message timestamps (light or dark). Any other
     * value leaves the stylesheet default in place.
     *
     * @var string
     */
    public $timestampMode;

    /**
     * Indicates if the contacts pane uses the light style, which paints the
     * pane over the subtle light background instead of over the inverted one.
     *
     * @var bool|mixed
     */
    public $contactsLight;

    /**
     * Indicates if the contacts pane is initiated on the open state.
     *
     * @var bool|mixed
     */
    public $contactsOpen;

    /**
     * Extra classes for the "card-header" element. This provides a way to
     * customize the card header container style.
     *
     * @var string
     */
    public $headerClass;

    /**
     * Extra classes for the "card-body" element. This provides a way to
     * customize the card body container style.
     *
     * @var string
     */
    public $bodyClass;

    /**
     * Extra classes for the "card-footer" element. This provides a way to
     * customize the card footer container style.
     *
     * @var string
     */
    public $footerClass;

    /**
     * Indicates if the card is collapsible. When enabled, a button to
     * collapse/expand the card will be available. If is set to 'collapsed'
     * string, the card will be initiated on collapsed mode.
     *
     * @var mixed
     */
    public $collapsible;

    /**
     * Indicates if the card is removable. When enabled, a button to remove
     * the card will be available.
     *
     * @var bool|mixed
     */
    public $removable;

    /**
     * Indicates if the card is maximizable. When enabled, a button to maximize
     * the card will be available.
     *
     * @var bool|mixed
     */
    public $maximizable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $icon = null, $theme = null, $badge = null,
        $badgeTheme = null, $height = null, $timestampMode = null,
        $contactsLight = null, $contactsOpen = null, $headerClass = null,
        $bodyClass = null, $footerClass = null, $collapsible = null,
        $removable = null, $maximizable = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->icon = $icon;
        $this->theme = $theme ?? static::DEFAULT_THEME;
        $this->badge = UtilsHelper::applyHtmlEntityDecoder($badge);
        $this->badgeTheme = $badgeTheme;
        $this->height = $this->resolveHeight($height);
        $this->timestampMode = $this->resolveTimestampMode($timestampMode);
        $this->contactsLight = $contactsLight;
        $this->contactsOpen = $contactsOpen;
        $this->headerClass = $headerClass;
        $this->bodyClass = $bodyClass;
        $this->footerClass = $footerClass;
        $this->collapsible = $collapsible;
        $this->removable = $removable;
        $this->maximizable = $maximizable;
    }

    /**
     * Resolve the height requested for the message and contacts panes into a
     * CSS length. A bare number is taken as pixels, and any value that is not
     * a plain length is dropped, so an arbitrary value can never reach the
     * generated style attribute.
     *
     * @param  mixed  $height  The height requested by the user
     * @return string|null
     */
    protected function resolveHeight($height)
    {
        if (is_numeric($height)) {
            return ((float) $height).'px';
        }

        if (! is_string($height)) {
            return null;
        }

        $height = trim($height);

        return preg_match('/^\d+(\.\d+)?(px|rem|em|vh|%)$/', $height)
            ? $height
            : null;
    }

    /**
     * Resolve the contrast mode of the message timestamps. Any mode out of
     * the accepted set is dropped, leaving the stylesheet default in place.
     *
     * @param  mixed  $mode  The mode requested by the user
     * @return string|null
     */
    protected function resolveTimestampMode($mode)
    {
        if (! is_string($mode)) {
            return null;
        }

        $mode = strtolower(trim($mode));

        return in_array($mode, static::TIMESTAMP_MODES, true) ? $mode : null;
    }

    /**
     * Check if the card is initiated on collapsed mode.
     *
     * @return bool
     */
    public function isCardCollapsed()
    {
        return $this->collapsible === 'collapsed';
    }

    /**
     * Make the class attribute for the direct chat card.
     *
     * @return string
     */
    public function makeCardClass()
    {
        $classes = ['card', 'direct-chat'];

        // The AdminLTE v4 stylesheet gives the cards no bottom margin, every
        // card of the reference layouts carries a 'mb-4' utility. It is only
        // added when the caller does not provide a margin of its own.

        if (! UtilsHelper::hasBottomMarginClass($this->attributes?->get('class'))) {
            $classes[] = 'mb-4';
        }

        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "direct-chat-{$theme}";
        }

        if (isset($this->timestampMode)) {
            $classes[] = "timestamp-{$this->timestampMode}";
        }

        // The class below is the one toggled by the AdminLTE 'DirectChat'
        // plugin, so an initially open pane keeps working with the toggle.

        if ($this->contactsOpen) {
            $classes[] = 'direct-chat-contacts-open';
        }

        if ($this->isCardCollapsed()) {
            $classes[] = 'collapsed-card';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the card header.
     *
     * @return string
     */
    public function makeCardHeaderClass()
    {
        $classes = ['card-header'];

        if (isset($this->headerClass)) {
            $classes[] = $this->headerClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the card body.
     *
     * @return string
     */
    public function makeCardBodyClass()
    {
        $classes = ['card-body'];

        if (isset($this->bodyClass)) {
            $classes[] = $this->bodyClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the card footer.
     *
     * @return string
     */
    public function makeCardFooterClass()
    {
        $classes = ['card-footer'];

        if (isset($this->footerClass)) {
            $classes[] = $this->footerClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the header badge.
     *
     * @return string
     */
    public function makeBadgeClass()
    {
        $classes = ['badge'];

        $theme = $this->resolveThemeColor($this->badgeTheme ?? $this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the contacts pane.
     *
     * @return string
     */
    public function makeContactsClass()
    {
        $classes = ['direct-chat-contacts'];

        if ($this->contactsLight) {
            $classes[] = 'direct-chat-contacts-light';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the inline style shared by the messages pane and by the contacts
     * pane. Both panes are stacked one over the other, so they lose their
     * alignment as soon as their heights differ.
     *
     * @return string|null
     */
    public function makePaneStyle()
    {
        return isset($this->height) ? "height: {$this->height};" : null;
    }

    /**
     * Check if the header badge holds some content.
     *
     * @return bool
     */
    public function hasBadge()
    {
        return isset($this->badge) && trim((string) $this->badge) !== '';
    }

    /**
     * Check if the card header is empty (no items defined for the header).
     *
     * @param  bool  $hasSlot  Whether the card tools slot is defined
     * @param  bool  $hasContactsSlot  Whether the contacts slot is defined
     * @return bool
     */
    public function isCardHeaderEmpty($hasSlot = false, $hasContactsSlot = false)
    {
        $hasTools = isset($this->collapsible) ||
                    isset($this->maximizable) ||
                    isset($this->removable) ||
                    $hasContactsSlot ||
                    $hasSlot;

        return empty($this->title) && empty($this->icon) &&
               ! $this->hasBadge() && ! $hasTools;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.direct-chat');
    }
}
