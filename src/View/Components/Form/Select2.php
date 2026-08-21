<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class Select2 extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The select2 plugin configuration parameters. Array with 'key => value'
     * pairs, where the key should be an existing configuration property of
     * the select2 plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'Select2' plugin (which still depends
     * on jQuery) together with the AdminLTE v4 compatibility stylesheet
     * ('adminlte-select2.min.css'). That stylesheet styles the plugin
     * 'default' theme, so the 'default' theme is used unless the user
     * explicitly configures another one.
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
        $this->config['theme'] = $this->config['theme'] ?? 'default';
        $this->enableOldSupport = isset($enableOldSupport);
    }

    /**
     * Make the class attribute for the input group item. Note we overwrite
     * the method of the parent class. Bootstrap 5 requires the "form-select"
     * class on the select elements.
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
        return view('adminlte::components.form.select2');
    }
}
