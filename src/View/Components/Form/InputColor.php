<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class InputColor extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The base set of classes for the input group item. Bootstrap 5 styles a
     * native color input with the 'form-control-color' modifier.
     *
     * @var array
     */
    protected $itemBaseClass = ['form-control', 'form-control-color'];

    /**
     * The legacy 'Bootstrap Colorpicker' plugin configuration parameters.
     *
     * DEPRECATED: AdminLTE v4 / Bootstrap 5 provide a native color control
     * ('form-control form-control-color'), so no plugin is required anymore.
     * The property is still accepted for backward compatibility, but its
     * content is fully ignored.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component does not require any plugin anymore.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null, $config = [], $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->config = is_array($config) ? $config : [];
        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Get the initial value for the color input. A native color input only
     * accepts a lowercase hexadecimal notation, so any other value is
     * discarded in favour of a neutral default.
     *
     * @param  string  $value  The value provided by the user, if any
     * @return string
     */
    public function makeColorValue($value = null)
    {
        $value = $this->getOldValue($this->errorKey, $value);

        if (is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
            return strtolower($value);
        }

        return '#000000';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-color');
    }
}
