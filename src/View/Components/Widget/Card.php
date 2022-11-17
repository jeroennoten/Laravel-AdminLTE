<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class Card extends Component
{
    /**
     * The title for the card header.
     *
     * @var string
     */
    public $title;

    /**
     * A Font Awesome icon for the card header.
     *
     * @var string
     */
    public $icon;

    /**
     * The card theme (light, dark, primary, secondary, info, success,
     * warning, danger or any other AdminLTE color like lighblue or teal).
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
        $title = null, $icon = null, $theme = null, $themeMode = null,
        $headerClass = null, $bodyClass = null, $footerClass = null,
        $disabled = null, $collapsible = null, $removable = null,
        $maximizable = null
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
    }

    /**
     * Make the class attribute for the card.
     *
     * @return string
     */
    public function makeCardClass()
    {
        $classes = ['card'];

        if (isset($this->theme)) {
            $base = $this->themeMode === 'full' ? 'bg-gradient' : 'card';
            $classes[] = "{$base}-{$this->theme}";

            if ($this->themeMode === 'outline') {
                $classes[] = 'card-outline';
            }
        }

        if ($this->collapsible === 'collapsed') {
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
     * Make the class attribute for the card title.
     *
     * @return string
     */
    public function makeCardTitleClass()
    {
        $classes = ['card-title'];

        if (isset($this->theme) && $this->themeMode === 'outline') {
            $classes[] = "text-{$this->theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Check if the card header is empty (no items defined for the header).
     *
     * @param  bool  $hasSlot  Whether the card header slot is defined
     * @return bool
     */
    public function isCardHeaderEmpty($hasSlot = false)
    {
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
