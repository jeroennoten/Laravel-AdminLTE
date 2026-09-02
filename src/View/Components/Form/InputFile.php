<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class InputFile extends InputGroupComponent
{
    /**
     * The placeholder for the input file box.
     *
     * DEPRECATED: Bootstrap 5 renders file inputs with the native browser
     * control, which does not support a placeholder. The property is still
     * accepted for backward compatibility, but it has no visual effect.
     *
     * @var string
     */
    public $placeholder;

    /**
     * A legend for the replacement of the default 'Browse' text. On Bootstrap 5
     * the browse button is rendered by the browser and can't be relabeled, so
     * the legend is rendered as an 'input-group-text' label attached to the
     * file input instead.
     *
     * @var string
     */
    public $legend;

    /**
     * Create a new component instance.
     * Note this component does not require any plugin anymore. Bootstrap 5
     * styles the native file input with the 'form-control' class.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $placeholder = '', $legend = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->legend = UtilsHelper::applyHtmlEntityDecoder($legend);
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-file');
    }
}
