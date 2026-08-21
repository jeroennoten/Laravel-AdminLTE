<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class Select extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class. Bootstrap 5 requires the "form-select"
     * class on the select elements (it replaces the legacy Bootstrap 4
     * "custom-select" class).
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-select'];

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
        return view('adminlte::components.form.select');
    }
}
