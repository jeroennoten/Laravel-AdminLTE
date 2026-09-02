<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Tool;

use Illuminate\View\Component;
use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\HandlesThemeColors;

class Modal extends Component
{
    use HandlesThemeColors;

    /**
     * The available modal sizes. Note the fullscreen sizes are provided by
     * Bootstrap 5 with an optional breakpoint suffix.
     *
     * @var array
     */
    protected $mSizes = [
        'sm', 'lg', 'xl', 'fullscreen', 'fullscreen-sm-down',
        'fullscreen-md-down', 'fullscreen-lg-down', 'fullscreen-xl-down',
        'fullscreen-xxl-down',
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
     * Indicates if the modal footer is suppressed. When enabled, neither the
     * default dismiss button nor the 'footerSlot' content are rendered.
     *
     * @var bool|mixed
     */
    public $disableFooter;

    /**
     * Extra classes for the modal dialog element (to customize the dialog
     * section).
     *
     * @var string
     */
    public $dialogClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id, $title = null, $icon = null, $size = null, $theme = null,
        $vCentered = null, $scrollable = null, $staticBackdrop = null,
        $disableAnimations = null, $disableFooter = null, $dialogClass = null
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
        $this->disableFooter = $disableFooter;
        $this->dialogClass = $dialogClass;
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

        if (! empty($this->dialogClass)) {
            $classes[] = $this->dialogClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the 'aria-labelledby' attribute for the modal. The attribute is
     * only emitted when there is a title, otherwise it would point to an
     * element without any accessible name.
     *
     * @return string
     */
    public function makeAriaLabelledBy()
    {
        if (! isset($this->title) || trim($this->title) === '') {
            return '';
        }

        return 'aria-labelledby="'.e($this->id).'-title"';
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

        if (! empty($theme) && ! UtilsHelper::hasDarkText($theme)) {
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
