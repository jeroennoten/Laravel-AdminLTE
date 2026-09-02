<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Card extends Component
{
    use HandlesThemeColors;

    /**
     * The set of tags accepted for the card title container. The AdminLTE
     * docs state the 'card-title' class is a style and not a heading level,
     * so the tag is up to the document outline of the underlying page.
     *
     * @var array
     */
    protected const TITLE_TAGS = [
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span',
    ];

    /**
     * The default tag for the card title container.
     *
     * @var string
     */
    protected const DEFAULT_TITLE_TAG = 'h3';

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
     * The card theme (light, dark, primary, secondary, info, success, warning
     * or danger). Any color of the AdminLTE extended palette (navy, sky, teal,
     * ...) is also supported when the 'adminlte.assets.extended_colors' option
     * is enabled. The AdminLTE v3 color names (lightblue, maroon, ...) are
     * still accepted and translated to their v4 equivalent.
     *
     * @var string
     */
    public $theme;

    /**
     * The theme mode (full or outline).
     *
     * @var string
     */
    public $themeMode;

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
     * Extra classes for the "card-title" element. This provides a way to
     * customize the card title style.
     *
     * @var string
     */
    public $titleClass;

    /**
     * The tag used for the card title container (h1, h2, h3, h4, h5, h6, div
     * or span). Any other value falls back to the default tag.
     *
     * @var string
     */
    public $titleTag;

    /**
     * The set of tabs of the card. When provided, the card header holds a
     * "nav-tabs" navigation instead of a title. Each entry accepts an 'id',
     * a 'label', an optional 'icon' and an optional 'active' flag.
     *
     * @var array
     */
    public $tabs;

    /**
     * Indicates if the card is disabled. When enabled, an overay will show
     * over the card.
     *
     * @var bool|mixed
     */
    public $disabled;

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
     * the card will be available. If is set to 'maximized' string, the card
     * will be initiated on maximized mode.
     *
     * @var mixed
     */
    public $maximizable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $icon = null, $theme = null, $themeMode = null,
        $headerClass = null, $bodyClass = null, $footerClass = null,
        $disabled = null, $collapsible = null, $removable = null,
        $maximizable = null, $titleClass = null, $titleTag = null,
        $tabs = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->icon = $icon;
        $this->theme = $theme;
        $this->themeMode = $themeMode;
        $this->headerClass = $headerClass;
        $this->bodyClass = $bodyClass;
        $this->footerClass = $footerClass;
        $this->disabled = $disabled;
        $this->removable = $removable;
        $this->collapsible = $collapsible;
        $this->maximizable = $maximizable;
        $this->titleClass = $titleClass;
        $this->titleTag = $this->resolveTitleTag($titleTag);
        $this->tabs = $this->resolveTabs($tabs);
    }

    /**
     * Resolve the tag to use for the card title container. Any tag out of the
     * allowed set falls back to the default one, so an arbitrary value can
     * never reach the generated markup.
     *
     * @param  mixed  $tag  The tag requested by the user
     * @return string
     */
    protected function resolveTitleTag($tag)
    {
        if (! is_string($tag)) {
            return self::DEFAULT_TITLE_TAG;
        }

        $tag = strtolower(trim($tag));

        return in_array($tag, self::TITLE_TAGS, true)
            ? $tag
            : self::DEFAULT_TITLE_TAG;
    }

    /**
     * Resolve the set of card tabs into a normalized array of items. Every
     * item is guaranteed to hold a safe 'id', a 'label', an 'icon' and an
     * 'active' flag, with exactly one item flagged as the active one.
     *
     * @param  mixed  $tabs  The set of tabs requested by the user
     * @return array
     */
    protected function resolveTabs($tabs)
    {
        if (! is_array($tabs) || empty($tabs)) {
            return [];
        }

        $items = [];
        $activeIdx = null;

        foreach ($tabs as $key => $tab) {
            if (! is_array($tab)) {
                $tab = ['label' => $tab];
            }

            $id = $tab['id'] ?? (is_string($key) ? $key : null);
            $id = UtilsHelper::applyHtmlEntityDecoder((string) $id);
            $id = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id);

            if ($id === '') {
                $id = 'card-tab-'.(count($items) + 1);
            }

            $label = $tab['label'] ?? $id;

            if (! isset($activeIdx) && ! empty($tab['active'])) {
                $activeIdx = count($items);
            }

            $items[] = [
                'id' => $id,
                'label' => UtilsHelper::applyHtmlEntityDecoder($label),
                'icon' => $tab['icon'] ?? null,
                'active' => false,
            ];
        }

        $items[$activeIdx ?? 0]['active'] = true;

        return $items;
    }

    /**
     * Check if the card is a tabbed card.
     *
     * @param  bool  $hasSlot  Whether the card tabs slot is defined
     * @return bool
     */
    public function hasTabs($hasSlot = false)
    {
        return $hasSlot || ! empty($this->tabs);
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
     * Check if the card is initiated on maximized mode.
     *
     * @return bool
     */
    public function isCardMaximized()
    {
        return $this->maximizable === 'maximized';
    }

    /**
     * Make the class attribute for the card.
     *
     * @param  bool  $hasTabsSlot  Whether the card tabs slot is defined
     * @return string
     */
    public function makeCardClass($hasTabsSlot = false)
    {
        $classes = ['card'];

        // The AdminLTE v4 stylesheet gives the cards no bottom margin, every
        // card of the reference layouts carries a 'mb-4' utility. It is only
        // added when the caller does not provide a margin of its own.

        if (! UtilsHelper::hasBottomMarginClass($this->attributes?->get('class'))) {
            $classes[] = 'mb-4';
        }

        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            if ($this->themeMode === 'full') {
                // On AdminLTE v4 there are no 'bg-gradient-{color}' classes
                // on the core stylesheet, a fully colored card is made with
                // the Bootstrap 'text-bg-{color}' helper.

                $classes[] = "text-bg-{$theme}";
            } else {
                $classes[] = "card-{$theme}";

                if ($this->themeMode === 'outline') {
                    $classes[] = 'card-outline';
                }
            }
        }

        if ($this->hasTabs($hasTabsSlot)) {
            $classes[] = 'card-tabs';

            // The outline cards get an extra modifier, which moves the accent
            // from the top border of the card to the tabs navigation.

            if ($this->themeMode === 'outline') {
                $classes[] = 'card-outline-tabs';
            }
        }

        if ($this->isCardCollapsed()) {
            $classes[] = 'collapsed-card';
        }

        if ($this->isCardMaximized()) {
            $classes[] = 'maximized-card';

            // The AdminLTE card plugin flags a card that was collapsed when
            // it got maximized, so restoring it returns to the collapsed
            // state. The same flag keeps the body visible while maximized.

            if ($this->isCardCollapsed()) {
                $classes[] = 'was-collapsed';
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the card header.
     *
     * @param  bool  $hasTabsSlot  Whether the card tabs slot is defined
     * @return string
     */
    public function makeCardHeaderClass($hasTabsSlot = false)
    {
        $classes = ['card-header'];

        // A tabbed card header holds the tabs navigation, which needs to sit
        // flush against the body seam.

        if ($this->hasTabs($hasTabsSlot)) {
            $classes[] = 'p-0';
            $classes[] = 'pt-1';
        }

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
     * Make the class attribute for the card title.
     *
     * @return string
     */
    public function makeCardTitleClass()
    {
        // Note the AdminLTE v4 outline cards keep a plain title, the theme
        // color is only applied to the top border of the card.

        $classes = ['card-title'];

        if (isset($this->titleClass)) {
            $classes[] = $this->titleClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for a card tab navigation link.
     *
     * @param  array  $tab  A normalized card tab item
     * @return string
     */
    public function makeTabLinkClass($tab)
    {
        $classes = ['nav-link'];

        if (! empty($tab['active'])) {
            $classes[] = 'active';
        }

        return implode(' ', $classes);
    }

    /**
     * Check if the card header is empty (no items defined for the header).
     *
     * @param  bool  $hasSlot  Whether the card tools slot is defined
     * @param  bool  $hasTitleSlot  Whether the card title slot is defined
     * @param  bool  $hasHeaderSlot  Whether the card header slot is defined
     * @param  bool  $hasTabsSlot  Whether the card tabs slot is defined
     * @return bool
     */
    public function isCardHeaderEmpty(
        $hasSlot = false, $hasTitleSlot = false, $hasHeaderSlot = false,
        $hasTabsSlot = false
    ) {
        if ($hasHeaderSlot || $hasTitleSlot || $this->hasTabs($hasTabsSlot)) {
            return false;
        }

        $hasTools = isset($this->collapsible) ||
                    isset($this->maximizable) ||
                    isset($this->removable) ||
                    $hasSlot;

        return empty($this->title) && empty($this->icon) && ! $hasTools;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.widget.card');
    }
}
