<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Layout;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class ContentHeader extends Component
{
    /**
     * The set of classes used by the AdminLTE v4 reference layouts on the
     * title of the content header.
     *
     * @var string
     */
    protected const DEFAULT_TITLE_CLASS = 'mb-0 fs-3';

    /**
     * The title for the content header.
     *
     * @var string
     */
    public $title;

    /**
     * The normalized set of breadcrumb items. Each item is an array with a
     * 'label', an 'url' (may be null) and an 'active' flag.
     *
     * @var array
     */
    public $breadcrumbs;

    /**
     * The classes for the title element. When not provided, the classes used
     * by the AdminLTE v4 reference layouts are applied.
     *
     * @var string
     */
    public $titleClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null, $breadcrumbs = [], $titleClass = null)
    {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->titleClass = $titleClass;
        $this->breadcrumbs = $this->makeBreadcrumbs($breadcrumbs);
    }

    /**
     * Normalize the set of breadcrumb items provided by the user. An item may
     * be a plain string (used as the label) or an array with a 'label', an
     * optional 'url' and an optional 'active' flag. When no 'active' flag is
     * given, an item without url is considered the active one.
     *
     * @param  mixed  $breadcrumbs  The set of breadcrumb items
     * @return array
     */
    protected function makeBreadcrumbs($breadcrumbs)
    {
        if (! is_array($breadcrumbs)) {
            return [];
        }

        $items = [];

        foreach ($breadcrumbs as $item) {
            $item = is_array($item) ? $item : ['label' => $item];
            $label = UtilsHelper::applyHtmlEntityDecoder($item['label'] ?? null);

            if (! isset($label) || trim((string) $label) === '') {
                continue;
            }

            $url = $item['url'] ?? null;

            $items[] = [
                'label' => $label,
                'url' => ! empty($url) ? $url : null,
                'active' => isset($item['active'])
                    ? boolval($item['active'])
                    : empty($url),
            ];
        }

        return $items;
    }

    /**
     * Make the class attribute for the content header title.
     *
     * @return string
     */
    public function makeTitleClass()
    {
        return isset($this->titleClass)
            ? $this->titleClass
            : self::DEFAULT_TITLE_CLASS;
    }

    /**
     * Make the class attribute for a breadcrumb item.
     *
     * @param  array  $item  The normalized breadcrumb item
     * @return string
     */
    public function makeBreadcrumbItemClass($item)
    {
        $classes = ['breadcrumb-item'];

        if (! empty($item['active'])) {
            $classes[] = 'active';
        }

        return implode(' ', $classes);
    }

    /**
     * Check if the breadcrumb column holds any item.
     *
     * @return bool
     */
    public function hasBreadcrumbs()
    {
        return ! empty($this->breadcrumbs);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.content-header');
    }
}
