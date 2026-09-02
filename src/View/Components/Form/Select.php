<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class Select extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The base set of classes for the input group item. Bootstrap 5 requires
     * the "form-select" class on the select elements (it replaces the legacy
     * Bootstrap 4 "custom-select" class).
     *
     * @var array
     */
    protected $itemBaseClass = ['form-select'];

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
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.select');
    }
}
