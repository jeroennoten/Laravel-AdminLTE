<?php

namespace JeroenNoten\LaravelAdminLte\Components;

use Illuminate\View\Component;

class InputGroupComponent extends Component
{
    /**
     * The name and id attribute for the input group item. The input group item
     * may be an "input", a "select", a "textarea", etc.
     *
     * @var string
     */
    public $name;

    /**
     * The label of the input group.
     *
     * @var string
     */
    public $label;

    /**
     * The input group size (you can specify 'sm' or 'lg').
     *
     * @var string
     */
    public $size;

    /**
     * Extra classes for the label container. This provides a way to customize
     * the label style.
     *
     * @var string
     */
    public $labelClass;

    /**
     * Extra classes for the "form-group" element. This provides a way to
     * customize the main container style.
     *
     * @var string
     */
    public $topClass;

    /**
     * Indicates if the invalid feedback is disabled for the input group.
     *
     * @var bool
     */
    public $disableFeedback;

    /**
     * Additional classes for "input-group" element. This provides a way to
     * customize the input group container style.
     *
     * @var string
     */
    public $inputGroupClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, $label = null, $size = null, $labelClass = null,
        $topClass = null, $inputGroupClass = null, $disableFeedback = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->size = $size;
        $this->topClass = $topClass;
        $this->labelClass = $labelClass;
        $this->inputGroupClass = $inputGroupClass;
        $this->disableFeedback = $disableFeedback;
    }

    /**
     * Make the class attribute for the "form-group" element.
     *
     * @return string
     */
    public function makeFormGroupClass()
    {
        $classes = ['form-group'];

        if (isset($this->topClass)) {
            $classes[] = $this->topClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the "input-group" element.
     *
     * @param string $invalid
     * @return string
     */
    public function makeInputGroupClass($invalid = null)
    {
        $classes = ['input-group'];

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "input-group-{$this->size}";
        }

        if (! empty($invalid) && ! isset($this->disableFeedback)) {
            $classes[] = 'adminlte-invalid-igroup';
        }

        if (isset($this->inputGroupClass)) {
            $classes[] = $this->inputGroupClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the input group item.
     *
     * @param string $invalid
     * @return string
     */
    public function makeItemClass($invalid = null)
    {
        $classes = ['form-control'];

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
        return view('adminlte::components.input-group-component');
    }
}
