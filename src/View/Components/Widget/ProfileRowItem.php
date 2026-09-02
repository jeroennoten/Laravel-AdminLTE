<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Widget;

class ProfileRowItem extends ProfileItem
{
    /**
     * The base set of classes for the text wrapper of the item.
     *
     * @var array
     */
    protected $textWrapperBaseClass = ['float-end'];

    /**
     * The item layout type. The 'default' type keeps the legacy markup (the
     * item is a 'div.col-{size}' holding a 'span.nav-link'), while the 'nav'
     * type emits the AdminLTE v4 reference markup (the item is a 'li.nav-item'
     * holding an 'a.nav-link', so it has to be placed inside an
     * 'ul.nav.flex-column' element).
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
        $title = null, $text = null, $icon = null, $size = 12,
        $badge = null, $url = null, $urlTarget = 'title',
        $textTooltip = null, $layoutType = 'default'
    ) {
        parent::__construct(
            $title, $text, $icon, $size, $badge, $url, $urlTarget, $textTooltip
        );

        // Setup the layout type.

        $this->layoutType = $layoutType;

        if (! in_array($this->layoutType, ['default', 'nav'])) {
            $this->layoutType = 'default';
        }
    }

    /**
     * Make the class attribute for the link of an item using the 'nav' layout
     * type. Note the AdminLTE v4 reference uses the Bootstrap 5 emphasis link
     * color, so the item is readable on both color modes.
     *
     * @return string
     */
    public function makeNavLinkClass()
    {
        $classes = ['nav-link'];

        if (! empty($this->url)) {
            $classes[] = 'link-body-emphasis';
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
        return view('adminlte::components.widget.profile-row-item');
    }
}
