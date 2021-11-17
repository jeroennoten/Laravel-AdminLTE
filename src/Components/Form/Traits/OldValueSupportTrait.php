<?php

namespace JeroenNoten\LaravelAdminLte\Components\Form\Traits;

trait OldValueSupportTrait
{
    /**
     * Enable the retrievement of the submitted value in case of validation
     * errors. This submitted value may be automatically shown when there is a
     * validation error.
     *
     * @var bool
     */
    protected $enableOldSupport;

    /**
     * Make the value attribute for an input item, with auto lookup for a
     * submitted value in case of validation errors.
     *
     * @param  string  $errorKey  The key name to lookup for validation errors
     * @param  mixed  $default  The default value for the input element
     * @return mixed
     */
    public function makeItemValue($errorKey, $default = null)
    {
        return $this->enableOldSupport ? old($errorKey, $default) : $default;
    }
}
