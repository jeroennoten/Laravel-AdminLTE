<?php

namespace JeroenNoten\LaravelAdminLte\Components\Form;

use Illuminate\View\Component;

class InputGroupComponent extends Component
{
    /**
     * The id attribute for the underlying input group item. The input group
     * item may be an "input", a "select", a "textarea", etc.
     *
     * @var string
     */
    public $id;

    /**
     * The name attribute for the underlying input group item. This value will
     * be used as the default id attribute when not provided. The input group
     * item may be an "input", a "select", a "textarea", etc.
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
     * Additional classes for "input-group" element. This provides a way to
     * customize the input group container style.
     *
     * @var string
     */
    public $igroupClass;

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
    public $fgroupClass;

    /**
     * Indicates if the invalid feedback is disabled for the input group.
     *
     * @var bool
     */
    public $disableFeedback;

    /**
     * The lookup key to use when searching for validation errors. The lookup
     * key is automatically generated from the name property. This provides a
     * way to overwrite that value.
     *
     * @var string
     */
    public $errorKey;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name, $id = null, $label = null, $igroupSize = null, $labelClass = null,
        $fgroupClass = null, $igroupClass = null, $disableFeedback = null,
        $errorKey = null
    ) {
        $this->id = $id ?? $name;
        $this->name = $name;
        $this->label = $label;
        $this->size = $igroupSize;
        $this->fgroupClass = $fgroupClass;
        $this->labelClass = $labelClass;
        $this->igroupClass = $igroupClass;
        $this->disableFeedback = $disableFeedback;

        // Setup the lookup key for validation errors.

        $this->errorKey = $errorKey ?? $this->makeErrorKey();
    }

    /**
     * Make the class attribute for the "form-group" element.
     *
     * @return string
     */
    public function makeFormGroupClass()
    {
        $classes = ['form-group'];

        if (isset($this->fgroupClass)) {
            $classes[] = $this->fgroupClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the "input-group" element.
     *
     * @return string
     */
    public function makeInputGroupClass()
    {
        $classes = ['input-group'];

        if (isset($this->size) && in_array($this->size, ['sm', 'lg'])) {
            $classes[] = "input-group-{$this->size}";
        }

        if ($this->isInvalid() && ! isset($this->disableFeedback)) {
            $classes[] = 'adminlte-invalid-igroup';
        }

        if (isset($this->igroupClass)) {
            $classes[] = $this->igroupClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Make the class attribute for the input group item.
     *
     * @return string
     */
    public function makeItemClass()
    {
        $classes = ['form-control'];

        if ($this->isInvalid() && ! isset($this->disableFeedback)) {
            $classes[] = 'is-invalid';
        }

        return implode(' ', $classes);
    }

    /**
     * Check if there exists validation errors on the session related to the
     * configured error key.
     *
     * @return bool
     */
    public function isInvalid()
    {
        // Get the errors bag from session. The errors bag will be an instance
        // of the Illuminate\Support\MessageBag class.

        $errors = session()->get('errors');

        // Check if exists any error related to the configured error key.

        return ! empty($errors) && ! empty($errors->first($this->errorKey));
    }

    /**
     * Make the error key that will be used to search for validation errors.
     * The error key is generated from the 'name' property.
     * Examples:
     * $name = 'files[]'         => $errorKey = 'files'.
     * $name = 'person[2][name]' => $errorKey = 'person.2.name'.
     *
     * @return string
     */
    protected function makeErrorKey()
    {
        $errKey = preg_replace('@\[\]$@', '', $this->name);

        return preg_replace('@\[([^]]+)\]@', '.$1', $errKey);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('adminlte::components.form.input-group-component');
    }
}
