<?php

namespace JeroenNoten\LaravelAdminLte\Components\Form;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Options extends Component
{
    /**
     * The list of options as key value pairs.
     *
     * @var array
     */
    public $options;

    /**
     * The list of selected option keys.
     *
     * @var array
     */
    public $selected;

    /**
     * Whether to use strict comparison between key and selections.
     *
     * @var bool
     */
    public $strict;

    /**
     * Create a new component instance.
     */
    public function __construct($options = null, $selected = null, $strict = null)
    {
        $this->options = Arr::wrap($options);
        $this->selected = Arr::wrap($selected);
        $this->strict = isset($strict);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.options');
    }
}
