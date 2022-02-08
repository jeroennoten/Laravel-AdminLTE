<?php

namespace JeroenNoten\LaravelAdminLte\View\Components\Form;

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
     * The list of disabled option keys.
     *
     * @var array
     */
    public $disabled;

    /**
     * Whether to use strict comparison between the option keys and the keys of
     * the selected/disabled options.
     *
     * @var bool
     */
    public $strict;

    /**
     * Whether to add a selectable empty option to the list of options. If the
     * value is a string, it will be used as the option label, otherwise no
     * label will be available for the empty option.
     *
     * @var bool|string
     */
    public $emptyOption;

    /**
     * Whether to add a placeholder (non-selectable option) to the list of
     * options. If the value is a string, it will be used as the placeholder
     * label, otherwise no label will be available for the placeholder.
     *
     * @var bool|string
     */
    public $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $options, $selected = null, $disabled = null,
        $strict = null, $emptyOption = null, $placeholder = null
    ) {
        $this->options = Arr::wrap($options);
        $this->selected = Arr::wrap($selected);
        $this->disabled = Arr::wrap($disabled);
        $this->strict = isset($strict);
        $this->emptyOption = $emptyOption;
        $this->placeholder = $placeholder;
    }

    /**
     * Determines if an option's key is on selected state.
     *
     * @param  string  $key  The option's key.
     * @return bool
     */
    public function isSelected($key)
    {
        return in_array($key, $this->selected, $this->strict);
    }

    /**
     * Determines if an option's key is on disabled state.
     *
     * @param  string  $key  The option's key.
     * @return bool
     */
    public function isDisabled($key)
    {
        return in_array($key, $this->disabled, $this->strict);
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
