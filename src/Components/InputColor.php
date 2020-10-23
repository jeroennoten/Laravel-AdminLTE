<?php

namespace JeroenNoten\LaravelAdminLte\Components;

class InputColor extends InputGroupComponent
{
    /**
     * The Bootstrap Colorpicker plugin configuration parameters. Array with
     * key => value pairs, where the key should be an existing configuration
     * property of the plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'Bootstrap Colorpicker' plugin.
     *
     * @return void
     */
    public function __construct(
        $name, $label = null, $size = null, $labelClass = null,
        $topClass = null, $disableFeedback = null, $config = []
    ) {
        parent::__construct(
            $name, $label, $size, $labelClass, $topClass, $disableFeedback
        );

        $this->config = is_array($config) ? $config : [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.input-color');
    }
}
