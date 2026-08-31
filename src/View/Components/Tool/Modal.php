<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Tool;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\HandlesThemeColors;

class Modal extends Component
{
    use HandlesThemeColors;

    /**
     * The available modal sizes.
     *
     * @var array
     */
    protected $mSizes = ['sm', 'lg', 'xl'];

    /**
     * The set of themes that provide a dark background. On these themes, the
     * close button of the modal header requires the dark color mode in order
     * to get enough contrast.
     *
     * @var array
     */
    protected $darkThemes = [
        'primary', 'secondary', 'success', 'danger', 'dark', 'indigo',
        'navy', 'violet', 'fuchsia', 'pink', 'olive', 'teal', 'steel',
        'slate', 'graphite', 'midnight',
    ];

    /**
     * The modal ID attribute, used to target the modal and show it.
     *
     * @var string
     */
    public $id;

    /**
     * The title for the modal header.
     *
     * @var string
     */
    public $title;

    /**
     * An icon for the modal header (a Bootstrap Icon by default).
     *
     * @var string
     */
    public $icon;

    /**
     * The modal size (sm, lg or xl).
     *
     * @var string
     */
    public $size;

    /**
     * The modal theme (light, dark, primary, secondary, info, success,
     * warning, danger or any other AdminLTE color like lighblue or teal).
     *
     * @var string
     */
    public $theme;

    /**
     * Indicates if the modal is vertically centered.
     *
     * @var bool|mixed
     */
    public $vCentered;

    /**
     * Indicates if the modal is scrollable. Enable this if the modal content
     * is large.
     *
     * @var bool|mixed
     */
    public $scrollable;

    /**
     * Indicates if the backdrop is static. When enabled, the modal will not
     * close when clicking outside it.
     *
     * @var bool|mixed
     */
    public $staticBackdrop;

    /**
     * Indicates if the show/hide fade animations are disabled.
     *
     * @var bool|mixed
     */
    public $disableAnimations;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id, $title = null, $icon = null, $size = null, $theme = null,
        $vCentered = null, $scrollable = null, $staticBackdrop = null,
        $disableAnimations = null
    ) {
        $this->id = $id;
        $this->title = UtilsHelper::applyHtmlEntityDecoder($title);
        $this->icon = $icon;
        $this->size = $size;
        $this->theme = $theme;
        $this->vCentered = $vCentered;
        $this->scrollable = $scrollable;
        $this->staticBackdrop = $staticBackdrop;
        $this->disableAnimations = $disableAnimations;
    }

    /**
     * Make the class attribute for the modal.
     *
     * @return string
     */
    public function makeModalClass()
    {
        $classes = ['modal'];

        if (! isset($this->disableAnimations)) {
            $classes[] = 'fade';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the modal dialog.
     *
     * @return string
     */
    public function makeModalDialogClass()
    {
        $classes = ['modal-dialog'];

        if (isset($this->vCentered)) {
            $classes[] = 'modal-dialog-centered';
        }

        if (isset($this->scrollable)) {
            $classes[] = 'modal-dialog-scrollable';
        }

        if (isset($this->size) && in_array($this->size, $this->mSizes)) {
            $classes[] = "modal-{$this->size}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the modal header.
     *
     * @return string
     */
    public function makeModalHeaderClass()
    {
        $classes = ['modal-header'];
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme)) {
            $classes[] = "text-bg-{$theme}";
        }

        return implode(' ', $classes);
    }

    /**
     * Make the data attributes for the modal header. Bootstrap 5.3 resolves
     * the close button color from the active color mode, so a dark themed
     * header needs to declare the dark color mode explicitly.
     *
     * @return string
     */
    public function makeModalHeaderData()
    {
        $theme = $this->resolveThemeColor($this->theme);

        if (! empty($theme) && in_array($theme, $this->darkThemes)) {
            return 'data-bs-theme="dark"';
        }

        return '';
    }

    /**
     * Make the class attribute for the close button of the modal footer. Note
     * the AdminLTE v4 modals always use a neutral dismiss button, the themed
     * colors are reserved for the affirmative actions.
     *
     * @return string
     */
    public function makeCloseButtonClass()
    {
        return 'btn btn-secondary';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.tool.modal');
    }
}
