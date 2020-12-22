<?php

namespace JeroenNoten\LaravelAdminLte\Components;

class SelectBs extends inputGroupComponent
{
    /**
     * The bootstrap-select plugin configuration parameters. Array with
     * key => value pairs, where the key should be an existing configuration
     * property of the bootstrap-select plugin.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new component instance.
     * Note this component requires the 'bootstrap-select' plugin.
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
     * Make the class attribute for the input group item.
     *
     * @param string $invalid
     * @return string
     */
    public function makeItemClass($invalid)
    {
        $classes = ['form-control'];

        if (! empty($invalid) && ! isset($this->disableFeedback)) {
            $classes[] = 'is-invalid';
        }

        // The next workaround setups the plugin when using sm/lg sizes.
        // Note: this may change with newer plugin versions.

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "form-control-{$this->size}";
            $classes[] = 'p-0';
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
        return view('adminlte::components.select-bs');
    }
}
