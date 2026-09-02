<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Layout;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\HandlesThemeColors;

class NavbarDropdownItem extends Component
{
    use HandlesThemeColors;

    /**
     * The title of the item. It fills the '.dropdown-item-title' element of
     * the media layout, the one used by the messages dropdown of the AdminLTE
     * v4 reference layouts.
     *
     * @var string
     */
    public $title;

    /**
     * The text of the item. On the media layout it's the message excerpt shown
     * below the title, on the inline layout it's the whole item text.
     *
     * @var string
     */
    public $text;

    /**
     * The time related to the item. On the media layout it's shown below the
     * text next to a clock icon, on the inline layout it's pushed to the end
     * of the item.
     *
     * @var string
     */
    public $time;

    /**
     * The icon of the item (a Bootstrap Icon). It's only used by the inline
     * layout, the media layout shows the image instead.
     *
     * @var string
     */
    public $icon;

    /**
     * The color of the item icon (an AdminLTE color).
     *
     * @var string
     */
    public $iconTheme;

    /**
     * The url of the image shown by the media layout. Providing an image (or
     * a title) switches the item to that layout.
     *
     * @var string
     */
    public $img;

    /**
     * The alternative text of the image. It defaults to an empty string, since
     * the image of a message item is decorative, the title next to it already
     * names the item.
     *
     * @var string
     */
    public $imgAlt;

    /**
     * The marker icon shown at the end of the title on the media layout (a
     * Bootstrap Icon). The reference layouts use it as a "starred message"
     * indicator.
     *
     * @var string
     */
    public $marker;

    /**
     * The color of the marker icon (an AdminLTE color).
     *
     * @var string
     */
    public $markerTheme;

    /**
     * The url of the item.
     *
     * @var string
     */
    public $url;

    /**
     * Whether a '.dropdown-divider' is emitted right after the item. The
     * navbar dropdowns of the AdminLTE v4 reference layouts separate every
     * item with one.
     *
     * @var bool
     */
    public $divider;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $title = null, $text = null, $time = null, $icon = null,
        $iconTheme = null, $img = null, $imgAlt = null, $marker = null,
        $markerTheme = null, $url = null, $divider = null
    ) {
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->text = UtilsHelper::applyHtmlEntityDecoder($text);
        $this->time = UtilsHelper::applyHtmlEntityDecoder($time);
        $this->icon = $icon;
        $this->iconTheme = $iconTheme;
        $this->img = $img;
        $this->imgAlt = UtilsHelper::applyHtmlEntityDecoder($imgAlt ?? '');
        $this->marker = $marker;
        $this->markerTheme = $markerTheme;
        $this->url = $url ?? '#';
        $this->divider = boolval($divider);
    }

    /**
     * Check whether the item uses the media layout, the one holding an image
     * and a '.dropdown-item-title' element.
     *
     * @return bool
     */
    public function isMediaItem()
    {
        return ! empty($this->img) || ! empty($this->title);
    }

    /**
     * Make the default attributes for the item anchor.
     *
     * @return array
     */
    public function makeAnchorDefaultAttrs()
    {
        return [
            'class' => 'dropdown-item',
            'href' => $this->url,
        ];
    }

    /**
     * Make the class attribute for the item icon.
     *
     * @return string
     */
    public function makeIconClass()
    {
        $classes = [$this->icon, 'me-2'];

        if (! empty($this->iconTheme)) {
            $classes[] = "text-{$this->resolveThemeColor($this->iconTheme)}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the marker shown next to the title.
     *
     * @return string
     */
    public function makeMarkerClass()
    {
        $classes = ['float-end fs-7'];

        if (! empty($this->markerTheme)) {
            $classes[] = "text-{$this->resolveThemeColor($this->markerTheme)}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the item image.
     *
     * @return string
     */
    public function makeImageClass()
    {
        return 'img-size-50 rounded-circle me-3';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.layout.navbar-dropdown-item');
    }
}
