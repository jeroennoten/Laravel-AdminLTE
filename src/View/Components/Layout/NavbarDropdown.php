<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Layout;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\HandlesThemeColors;

class NavbarDropdown extends Component
{
    use HandlesThemeColors;

    /**
     * The set of sizes provided by the AdminLTE v4 stylesheet for a navbar
     * dropdown menu ('.dropdown-menu-lg' and '.dropdown-menu-xl'). Any other
     * value leaves the menu on the default Bootstrap width.
     *
     * @var array
     */
    protected const MENU_SIZES = ['lg', 'xl'];

    /**
     * The default size for the dropdown menu. It's the one used by every
     * navbar dropdown of the AdminLTE v4 reference layouts.
     *
     * @var string
     */
    protected const DEFAULT_MENU_SIZE = 'lg';

    /**
     * The set of alignments accepted for the dropdown menu.
     *
     * @var array
     */
    protected const MENU_ALIGNMENTS = ['start', 'end'];

    /**
     * The default alignment for the dropdown menu. The navbar dropdowns of the
     * AdminLTE v4 reference layouts are always aligned to the end.
     *
     * @var string
     */
    protected const DEFAULT_MENU_ALIGNMENT = 'end';

    /**
     * The id attribute for the underlying <li> wrapper.
     *
     * @var string
     */
    public $id;

    /**
     * The id attribute for the anchor that toggles the dropdown menu. It's
     * always available, since the menu refers to it through the
     * 'aria-labelledby' attribute.
     *
     * @var string
     */
    public $toggleId;

    /**
     * The icon of the dropdown toggle (a Bootstrap Icon).
     *
     * @var string
     */
    public $icon;

    /**
     * The color of the dropdown toggle icon (an AdminLTE color).
     *
     * @var string
     */
    public $iconTheme;

    /**
     * The visible text of the dropdown toggle, placed next to the icon.
     *
     * @var string
     */
    public $text;

    /**
     * The accessible name of the dropdown toggle. It's required whenever the
     * toggle holds no visible text, otherwise the control is announced by its
     * icon markup alone.
     *
     * @var string
     */
    public $label;

    /**
     * The label of the navbar badge attached to the dropdown toggle.
     *
     * @var string
     */
    public $badge;

    /**
     * The background color of the navbar badge (an AdminLTE color).
     *
     * @var string
     */
    public $badgeTheme;

    /**
     * The size of the dropdown menu ('lg' or 'xl').
     *
     * @var string
     */
    public $size;

    /**
     * The alignment of the dropdown menu ('start' or 'end').
     *
     * @var string
     */
    public $align;

    /**
     * Whether the dropdown menu fades in through the AdminLTE 'flipInX'
     * animation. Note the stylesheet keys that animation on an '.open' class
     * over the dropdown wrapper, a Bootstrap 4 leftover that Bootstrap 5 does
     * not emit anymore, so the component bridges it on the client side.
     *
     * @var bool
     */
    public $animated;

    /**
     * Whether the dropdown toggle shows the Bootstrap caret.
     *
     * @var bool
     */
    public $caret;

    /**
     * The text of the dropdown menu header.
     *
     * @var string
     */
    public $header;

    /**
     * The text of the dropdown menu footer link.
     *
     * @var string
     */
    public $footer;

    /**
     * The url of the dropdown menu footer link.
     *
     * @var string
     */
    public $footerUrl;

    /**
     * Extra classes for the "dropdown-menu" element. This provides a way to
     * customize the dropdown menu style.
     *
     * @var string
     */
    public $menuClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id = null, $icon = null, $iconTheme = null, $text = null,
        $label = null, $badge = null, $badgeTheme = null, $size = null,
        $align = null, $animated = null, $caret = null, $header = null,
        $footer = null, $footerUrl = null, $menuClass = null
    ) {
        $this->id = $id;
        $this->icon = $icon;
        $this->iconTheme = $iconTheme;
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->label = UtilsHelper::applyHtmlEntityDecoder($label);
        $this->badge = UtilsHelper::applyHtmlEntityDecoder($badge);
        $this->badgeTheme = $badgeTheme;
        $this->size = $this->resolveMenuSize($size);
        $this->align = $this->resolveMenuAlignment($align);
        $this->animated = boolval($animated);
        $this->caret = boolval($caret);
        $this->header = UtilsHelper::applyHtmlEntityDecoder($header);
        $this->footer = UtilsHelper::applyHtmlEntityDecoder($footer);
        $this->footerUrl = $footerUrl ?? '#';
        $this->menuClass = $menuClass;
        $this->toggleId = $this->resolveToggleId($id);
    }

    /**
     * Resolve the size of the dropdown menu. A size out of the supported set
     * leaves the menu on the default Bootstrap width.
     *
     * @param  mixed  $size  The size requested by the user
     * @return string|null
     */
    protected function resolveMenuSize($size)
    {
        if (! isset($size)) {
            return self::DEFAULT_MENU_SIZE;
        }

        if (! is_string($size)) {
            return null;
        }

        $size = strtolower(trim($size));

        return in_array($size, self::MENU_SIZES, true) ? $size : null;
    }

    /**
     * Resolve the alignment of the dropdown menu. Any value out of the
     * supported set falls back to the default alignment.
     *
     * @param  mixed  $align  The alignment requested by the user
     * @return string
     */
    protected function resolveMenuAlignment($align)
    {
        if (! is_string($align)) {
            return self::DEFAULT_MENU_ALIGNMENT;
        }

        $align = strtolower(trim($align));

        return in_array($align, self::MENU_ALIGNMENTS, true)
            ? $align
            : self::DEFAULT_MENU_ALIGNMENT;
    }

    /**
     * Resolve the id of the dropdown toggle. It's derived from the id of the
     * wrapper when available, otherwise a unique one is generated, since the
     * dropdown menu always refers to the toggle.
     *
     * @param  mixed  $id  The id of the wrapper
     * @return string
     */
    protected function resolveToggleId($id)
    {
        $id = is_string($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', $id) : '';

        return $id === ''
            ? uniqid('adminlte-navbar-dropdown-')
            : "{$id}-toggle";
    }

    /**
     * Make the class attribute for the list item.
     *
     * @return string
     */
    public function makeListItemClass()
    {
        return 'nav-item dropdown';
    }

    /**
     * Make the class attribute for the dropdown toggle.
     *
     * @return string
     */
    public function makeToggleClass()
    {
        $classes = ['nav-link'];

        if (! $this->caret) {
            return implode(' ', $classes);
        }

        $classes[] = 'dropdown-toggle';

        // The '.dropdown-icon' class only drops the left margin of the caret,
        // which is the spacing wanted when the toggle holds an icon alone.

        if (empty($this->text)) {
            $classes[] = 'dropdown-icon';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the dropdown toggle icon.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = [$this->icon];

        if (! empty($this->iconTheme)) {
            $classes[] = "text-{$this->resolveThemeColor($this->iconTheme)}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the navbar badge.
     *
     * @return string
     */
    public function makeBadgeClass()
    {
        // Note the '.navbar-badge' class already provides the size, the
        // weight and the position of the badge on AdminLTE v4.

        $classes = ['navbar-badge badge'];

        if (! empty($this->badgeTheme)) {
            $classes[] = "text-bg-{$this->resolveThemeColor($this->badgeTheme)}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the dropdown menu.
     *
     * @return string
     */
    public function makeMenuClass()
    {
        $classes = ['dropdown-menu'];

        if (! empty($this->size)) {
            $classes[] = "dropdown-menu-{$this->size}";
        }

        $classes[] = "dropdown-menu-{$this->align}";

        if ($this->animated) {
            $classes[] = 'animated-dropdown-menu';
        }

        if (isset($this->menuClass)) {
            $classes[] = $this->menuClass;
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
        return view('adminlte::components.layout.navbar-dropdown');
    }
}
