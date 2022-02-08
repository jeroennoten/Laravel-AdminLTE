<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

class Select2 extends InputGroupComponent
{
    use Traits\OldValueSupportTrait;

    /**
     * The select2 plugin configuration parameters. Array with key => value
     * pairs, where the key should be an existing configuration property of
     * the select2 plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'select2' plugin and the 'bootstrap4'
     * css theme.
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
        $this->config['theme'] = 'bootstrap4';
        $this->enableOldSupport = isset($enableOldSupport);
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
