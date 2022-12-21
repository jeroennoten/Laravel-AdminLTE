<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class InputFile extends InputGroupComponent
{
    /**
     * The placeholder for the input file box.
     *
     * @var string
     */
    public $placeholder;

    /**
     * A legend for the replacement of the default 'Browser' text.
     *
     * @var string
     */
    public $legend;

    /**
     * Create a new component instance.
     * Note this component requires the 'bs-custom-input-file' plugin.
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

        $this->legend = $legend;
        $this->placeholder = $placeholder;
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['custom-file-input'];

        if ($this->isInvalid()) {
            $classes[] = 'is-invalid';
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
        return view('adminlte::components.form.input-file');
    }
}
