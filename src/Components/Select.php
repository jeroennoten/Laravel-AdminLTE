<?php

namespace JeroenNoten\LaravelAdminLte\Components;

class Select extends InputGroupComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, $label = null, $size = null,
        $labelClass = null, $topClass = null, $disableFeedback = null
    ) {
        parent::__construct(
            $name, $label, $size, $labelClass, $topClass, $disableFeedback
        );
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
        return view('adminlte::components.select');
    }
}
