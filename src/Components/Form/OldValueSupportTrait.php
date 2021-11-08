<?php

namespace JeroenNoten\LaravelAdminLte\Components\Form;

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
     * Make the value attribute for an input item.
     * 
     * @param  string  $errKey  The key name to lookup for validation errors
     * @param  mixed  $value  The current value of the item
     * @return mixed
     */
    public function makeItemValue($errorKey, $value = null)
    {
        if ($this->enableOldSupport) {
            $value = old($errorKey, $value);
        }

        return $value;
    }
}