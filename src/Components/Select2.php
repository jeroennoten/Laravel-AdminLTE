<?php

namespace JeroenNoten\LaravelAdminLte\Components;

class Select2 extends InputGroupComponent
{
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
        $name, $label = null, $size = null, $labelClass = null,
        $topClass = null, $disableFeedback = null, $config = []
    ) {
        parent::__construct(
            $name, $label, $size, $labelClass, $topClass, $disableFeedback
        );

        $this->config = is_array($config) ? $config : [];
        $this->config['theme'] = 'bootstrap4';
    }

    /**
     * Make the class attribute for the input group item.
     *
     * @param string $invalid
     * @return string
     */
    public function makeItemClass($invalid)
    {
        $classes = ['form-control', 'w-100'];

        if (! empty($invalid) && ! isset($this->disableFeedback)) {
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
        return view('adminlte::components.select2');
    }
}
