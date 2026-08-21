<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

use JeroenNoten\LaravelAdminLte\Helpers\UtilsHelper;

class InputSwitch extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The legacy 'Bootstrap Switch' plugin configuration parameters. Array with
     * 'key => value' pairs.
     *
     * On AdminLTE v4 the switch is rendered with the native Bootstrap 5.3
     * 'form-check form-switch' markup, so most of the legacy properties have no
     * effect anymore. The properties that are still honoured are:
     * - 'state'    => the initial checked state of the switch.
     * - 'disabled' => whether the switch is disabled.
     * - 'readonly' => whether the switch is readonly.
     * - 'onColor'  => a Bootstrap theme name used for the checked state color.
     * - 'labelText', 'onText' => the visible label next to the switch.
     *
     * Any other legacy property ('size', 'animate', 'handleWidth', 'inverse',
     * 'offColor', 'offText', 'wrapperClass', etc.) is still accepted for
     * backward compatibility and silently ignored.
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
        $errorKey = null, $config = [], $isChecked = null,
        $enableOldSupport = null
    ) {
        parent::__construct(
            $name, $id, $label, $igroupSize, $labelClass, $fgroupClass,
            $igroupClass, $disableFeedback, $errorKey
        );

        $this->config = is_array($config) ? $config : [];

        if (isset($isChecked)) {
            $this->config['state'] = ! empty($isChecked);
        }

        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Make the class attribute for the "input-group" element. Note we overwrite
     * the method of the parent class.
     *
     * @return string
     */
    public function makeInputGroupClass()
    {
        $classes = ['input-group'];

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "input-group-{$this->size}";
        }

        if ($this->isInvalid()) {
            $classes[] = 'adminlte-invalid-iswgroup';
        }

        if (isset($this->igroupClass)) {
            $classes[] = $this->igroupClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class. Bootstrap 5.3 requires the
     * 'form-check-input' class on the switch control.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-check-input'];

        if ($this->isInvalid()) {
            $classes[] = 'is-invalid';
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the wrapper of the native Bootstrap 5.3
     * switch control.
     *
     * @return string
     */
    public function makeSwitchWrapperClass()
    {
        return 'form-check form-switch d-flex align-items-center ps-0 gap-2';
    }

    /**
     * Determine whether the switch should be rendered on the checked state.
     * The previously submitted value takes precedence when the old value
     * support is enabled.
     *
     * @return bool
     */
    public function isChecked()
    {
        $errors = $this->errorsBag ?? session()->get('errors');

        if ($this->enableOldSupport && ! empty($errors) && $errors->isNotEmpty()) {
            return ! empty($this->getOldValue($this->errorKey));
        }

        return ! empty($this->config['state']);
    }

    /**
     * Get the visible label to display next to the switch, if any. The legacy
     * 'labelText' and 'onText' plugin properties are used as the source.
     *
     * @return string|null
     */
    public function getSwitchLabel()
    {
        $text = $this->config['labelText'] ?? $this->config['onText'] ?? null;

        return isset($text) ? UtilsHelper::applyHtmlEntityDecoder($text) : null;
    }

    /**
     * Get the Bootstrap theme name to use for the checked state of the switch.
     * The legacy 'onColor' plugin property is used as the source.
     *
     * @return string|null
     */
    public function getSwitchColor()
    {
        $themes = [
            'primary', 'secondary', 'success', 'info', 'warning', 'danger',
            'light', 'dark',
        ];

        $color = $this->config['onColor'] ?? null;

        return in_array($color, $themes, true) ? $color : null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-switch');
    }
}
