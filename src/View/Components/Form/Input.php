<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class Input extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * Unescaped the data
     *
     * @var bool
     */
    public $isEscaped;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $enableOldSupport = null, $isEscaped = true
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->isEscaped = $isEscaped;

        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input');
    }
}
