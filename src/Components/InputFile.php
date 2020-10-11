<?php

namespace JeroenNoten\LaravelAdminLte\Components;

class InputFile extends InputGroupComponent
{
    /**
     * The placeholder for the input file box.
     *
     * @var string
     */
    public $placeholder;

    /**
     * A legend for replace the default 'Browse' text.
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
        $name, $label = null, $size = null, $labelClass = null, $topClass = null,
        $disableFeedback = null, $placeholder = '', $legend = null
    ) {
        parent::__construct(
            $name, $label, $size, $labelClass, $topClass, $disableFeedback
        );

        $this->legend = $legend;
        $this->placeholder = $placeholder;
    }

    /**
     * Make the class attribute for the input group item.
     *
     * @param string $invalid
     * @return string
     */
    public function makeItemClass($invalid)
    {
        $classes = ['custom-file-input'];

        if (! empty($invalid) && ! isset($this->disableFeedback)) {
            $classes[] = 'is-invalid';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the custom file label item.
     *
     * @return string
     */
    public function makeCustomFileLabelClass()
    {
        $classes = ['custom-file-label', 'text-truncate'];

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "col-form-label-{$this->size}";
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
        return view('adminlte::components.input-file');
    }
}
